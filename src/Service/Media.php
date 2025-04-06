<?php

namespace LFPhp\WeworkSdk\Service;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use LFPhp\Logger\Logger;
use LFPhp\WeworkSdk\Base\AuthorizedService;
use function LFPhp\Func\format_size;

/**
 * 素材上传
 * 图片（image）：2MB，支持JPG,PNG格式
 * 语音（voice） ：2MB，播放长度不超过60s，仅支持AMR格式
 * 视频（video） ：10MB，支持MP4格式
 * 普通文件（file）：20MB
 */
class Media extends AuthorizedService {
	const TYPE_IMAGE = 'image';
	const TYPE_VOICE = 'voice';
	const TYPE_VIDEO = 'video';
	const TYPE_FILE = 'file';

	const SIZE_MAP = [
		self::TYPE_IMAGE => 10*1024*1024,
		self::TYPE_VOICE => 2*1024*1024,
		self::TYPE_VIDEO => 10*1024*1024,
		self::TYPE_FILE  => 20*1024*1024,
	];

	/**
	 * 上传临时素材
	 * @param string $type
	 * @param string $file 文件
	 * @param string $file_name 文件名
	 * @return array [media_id, created_at]
	 * @throws Exception
	 * @throws GuzzleException
	 */
	public static function uploadMedia($type, $file, $file_name){
		$logger = Logger::instance(__CLASS__);
		self::validateFile($type, $file);
		$client = new Client();
		$access_token = self::getAccessToken();
		$response = $client->request('POST', self::WEWORK_HOST.'/cgi-bin/media/upload?access_token='.$access_token.'&type='.$type, [
			'multipart' => [
				[
					'name'     => 'media',
					'filename' => $file_name,
					'contents' => fopen($file, 'r'),
				],
			],
		]);
		$logger->info(__METHOD__, $response);
		$resArr = @json_decode($response->getbody()->getContents(), true);
		if(!$resArr){
			throw new Exception('Response not in JSON format:'.$response->getBody()->getContents());
		}
		if($resArr['errcode'] != 0){
			throw new Exception('上传失败：'.$resArr['errmsg']);
		}
		return [$resArr['media_id'], $resArr['created_at']];
	}

	/**
	 * 上传图片（永久有效）
	 * 上传图片得到图片URL，该URL永久有效
	 * 上返回的图片URL，仅能用于图文消息正文中的图片展示，或者给客户发送欢迎语等；若用于非企业微信环境下的页面，图片将被屏蔽。
	 * 上每个企业每天最多可上传100张图片
	 * @param $file
	 * @return string 图片URL
	 * @throws Exception
	 * @throws GuzzleException
	 */
	public static function uploadImagePermanent($file){
		self::validateFile(self::TYPE_IMAGE, $file);
		$client = new Client();
		$access_token = self::getAccessToken();
		$response = $client->request('POST', self::WEWORK_HOST.'/cgi-bin/media/uploadimg?access_token='.$access_token, [
			'multipart' => [
				[
					'name'     => 'file_name',
					'contents' => fopen($file, 'r'),
				],
			],
		]);
		Logger::instance(__CLASS__)->info(__METHOD__, $response);
		$resArr = json_decode($response->getbody()->getContents(), true);
		if($resArr['errcode'] != 0){
			throw new Exception('上传失败：'.$resArr['errmsg']);
		}
		return $resArr['url'];
	}

	/**
	 * 获取媒体URL
	 * @param $media_id
	 * @return string
	 * @throws Exception
	 */
	public static function getTemporaryMediaUrlByMediaId($media_id){
		$token = self::getAccessToken();
		return self::WEWORK_HOST."/cgi-bin/media/get?access_token={$token}&media_id={$media_id}";
	}

	/**
	 * 上传临时图片（三天有效）
	 * @param $file
	 * @param $filename
	 * @return string 图片URL
	 * @throws GuzzleException
	 * @throws Exception
	 */
	public static function uploadImageTemporary($file, $filename){
		self::validateFile(self::TYPE_IMAGE, $file);
		[$media_id] = self::uploadMedia(self::TYPE_IMAGE, $file, $filename);
		return self::getTemporaryMediaUrlByMediaId($media_id);
	}

	/**
	 * 检查媒体大小限制
	 * @param $type string 文件类型  Media::SIZE_MAP
	 * @param $file
	 */
	private static function validateFile($type, $file){
		if(!is_file($file)){
			throw new Exception('文件不存在', -1, $file);
		}
		$filesize = filesize($file);
		if(!$filesize){
			throw new Exception('文件大小为空');
		}
		if($filesize >= self::SIZE_MAP[$type]){
			throw new Exception('上传失败，图片文件大小 '.format_size($filesize).' 超出系统规定（'.format_size(self::SIZE_MAP[$type]).')');
		}
	}
}
