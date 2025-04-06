<?php

namespace LFPhp\WeworkSdk\Base;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use LFPhp\WeworkSdk\Exception\ConnectException;

/**
 * 已授权access_token服务
 * Class AuthorizedService
 * @package LFPhp\WeworkSdk\Base
 */
abstract class AuthorizedService extends Service {
	private static $access_token;
	private static $agent_access_token;

	/**
	 * 设置公共授权access token
	 * @param string $access_token
	 */
	public static function setAccessToken($access_token){
		self::$access_token = $access_token;
	}

	/**
	 * 设置agent access token
	 * @param $agent_access_token
	 */
	public static function setAgentAccessToken($agent_access_token){
		self::$agent_access_token = $agent_access_token;
	}

	/**
	 * 获取agent access token
	 * @throws Exception
	 */
	public static function getAgentAccessToken(){
		return self::$agent_access_token;
	}

	/**
	 * 获取token
	 * @return string
	 * @throws Exception
	 */
	public static function getAccessToken(){
		if(!self::$access_token){
			throw new Exception('Access token required, please set by AuthorizedService::setAccessToken()');
		}
		return self::$access_token;
	}

	/**
	 * 重写sendRequest，增加access_token参数
	 * @param $url
	 * @param array $param
	 * @param bool $in_post
	 * @param array $files
	 * @param int $retries 重试次数 默认为0 代表不重试 仅请求一次
	 * @return Response
	 * @throws ConnectException
	 * @throws GuzzleException
	 */
	public static function sendRequest($url, array $param = [], $in_post = true, $files = [], $retries = 0){
		$url .= (strpos($url, '?') !== false ? '&' : '?').'access_token='.static::getAccessToken();
		if(!$in_post){
			$param['access_token'] = self::getAccessToken();
		}
		$result = null;
		for($i = 0; $i <= $retries; $i++){
			try{
				$result = parent::sendRequest($url, $param, $in_post, $files);
				break;
			}catch(\GuzzleHttp\Exception\ConnectException $e){
				//请求超时
				if(strrpos($e->getMessage(), 'Empty reply from server') !== false || strrpos($e->getMessage(), 'Operation timed out') !== false){
					if($i == $retries){
						//尝试重试$retries次后还是失败 抛出异常 让系统捕获记录异常日志
						throw $e;
					}
					//重试
					sleep(mt_rand(1, 3));
					continue;
				}
			}
		}
		return $result;
	}
}
