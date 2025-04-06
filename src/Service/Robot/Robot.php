<?php

namespace LFPhp\WeworkSdk\Service\Robot;

use Exception;
use InvalidArgumentException;
use function LFPhp\Func\curl_post_json;
use function LFPhp\Func\curl_query_json_success;

class Robot {
	public static function sendMessage($robot_key, $message){
		if(!$message['msgtype']){
			throw new InvalidArgumentException('message format error, [msgtype] required');
		}
		$url = "https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=$robot_key";
		$ret = curl_post_json($url, $message);
		if(!curl_query_json_success($ret, $json, $error)){
			throw new Exception($error);
		}
		if($json['err']){
			throw new Exception($json['err']);
		}
		return $json;
	}
}
