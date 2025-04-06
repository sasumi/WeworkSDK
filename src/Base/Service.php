<?php

namespace LFPhp\WeworkSdk\Base;

use Exception;
use LFPhp\Logger\Logger;
use function LFPhp\Func\curl_get;
use function LFPhp\Func\curl_post_file;
use function LFPhp\Func\curl_post_json;
use function LFPhp\Func\curl_query_json_success;

abstract class Service {
	const WEWORK_HOST = 'https://qyapi.weixin.qq.com';

	protected static function getJson($url, $param){
		$curl_ret = curl_get($url, $param);
		if(!curl_query_json_success($curl_ret, $json, $error)){
			throw new Exception($error);
		}
		return self::handleResponse($curl_ret);
	}

	protected static function postJson($url, $param){
		$curl_ret = curl_post_json($url, $param);
		if(!curl_query_json_success($curl_ret, $json, $error)){
			throw new Exception($error);
		}
		return self::handleResponse($curl_ret);
	}

	protected static function postFile($url, $param, $file_map){
		$curl_ret = curl_post_file($url, $file_map, $param);
		return self::handleResponse($curl_ret);
	}

	private static function handleResponse($curl_ret){
		if(!curl_query_json_success($curl_ret, $json, $error)){
			throw new Exception($error);
		}

		//解析企业微信统一返回结果格式标准，如有变更，需要考虑是否在子类中实现
		//企业微信接口有时候没有errorcode, 例如get_suite_access_token
		//https://work.weixin.qq.com/api/doc/90001/90143/90600
		if(isset($json['errcode']) && $json['errcode'] == 0){
			return Response::success($json['errmsg'] ?: 'Success', $json);
		}
		if(!isset($json['errcode'])){
			return Response::success('Success', $json);
		}

		[$friendly_msg, $tech_msg] = ReturnCode::getMessages($json['errcode']);
		if(!$friendly_msg){
			$friendly_msg = $json['errmsg'];
		}

		$logger = Logger::instance(__CLASS__);
		$logger->error('Service response error', $json, $tech_msg);
		return Response::error($friendly_msg, array_merge($json, ['tech_msg' => $tech_msg]), $json['errcode']);
	}
}
