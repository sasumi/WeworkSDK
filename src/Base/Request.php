<?php

namespace LFPhp\WeworkSdk\Base;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use LFPhp\Logger\Logger;
use LFPhp\WeworkSdk\Exception\ConnectException;

class Request {
	const DEFAULT_TIMEOUT = 30;
	const HTTP_CODE_SUCCESS = 200;

	private $config = [
		'timeout' => self::DEFAULT_TIMEOUT,
		'host'    => '',
	];

	private function __construct(array $config = []){
		$this->config = array_merge($this->config, $config);
	}

	/**
	 * singleton
	 * @param array $config
	 * @return static
	 */
	public static function instance(array $config = []){
		static $instance_list;
		$key = serialize($config);
		if(!$instance_list || !isset($instance_list[$key])){
			$instance_list[$key] = new self($config);
		}
		return $instance_list[$key];
	}

	/**
	 * send request in json mode
	 * @param string $url
	 * @param array $param
	 * @param bool $in_post
	 * @param array $files 文件列表，结构与$_FILE一致，
	 * 必须是平铺的文件列表，注意：如果文件名里面包含数组，PHP将混淆这里的结构！！！
	 * @return array
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function sendInJson($url, $param, $in_post, $files = []){
		if(!$this->config['host']){
			throw new Exception('Request host required');
		}
		$client = new Client([
			'base_uri' => $this->config['host'],
			'timeout'  => $this->config['timeout'],
		]);

		$logger = Logger::instance(__CLASS__);
		$logger->debug('Request start', $url, $param);

		if(!$in_post && $files){
			throw new Exception('Upload file require [POST] method');
		}

		if($in_post){
			$options = [RequestOptions::JSON => $param];
			if($files){
				$options['multipart'] = [];
				foreach($files as $file_info){
					$options['multipart'][] = [
						'name'     => 'image',
						'filename' => $file_info['filename'],
						'contents' => file_get_contents($file_info['tmp_name']),
						'headers'  => ['Content-Type' => 'image/jpg'],
					];
					$logger->info('Upload file', $file_info['tmp_name'], filesize($file_info['tmp_name']));
				}
			}
			$logger->info('Req start [post]', $url, $files, $options);
			$response = $client->request('post', $url, $options);
		}else{
			//修正如果是GET方式请求，url中的请求参数将被截取掉
			$tmp = parse_url($url);
			if(isset($tmp['query'])){
				parse_str($tmp['query'], $patch_param);
				if($patch_param){
					$param = array_merge($param, $patch_param);
				}
			}
			$logger->info('Req start [get]', $url, $param);
			$response = $client->request('get', $url, [RequestOptions::QUERY => $param]);
		}
		$content = $response->getBody()->getContents();
		$status_code = $response->getStatusCode();
		$logger->info("Request finish [$status_code]", $content);

		if($status_code != self::HTTP_CODE_SUCCESS){
			$logger->warning('Response http code error:'.$status_code);
			throw new ConnectException($url, $param, $content, $client);
		}

		$response_json = json_decode($content, true);
		if(!isset($response_json)){
			$logger->warning('Response string decode fail');
			throw new ConnectException($url, $param, $content, $client);
		}
		return $response_json;
	}

	private function __clone(){
	}
}
