<?php

namespace LFPhp\WeworkSdk\Base;

use Exception;

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

	public static function getJson($url, $param = []){
		$param['access_token'] = self::getAccessToken();
		return parent::getJson($url, $param);
	}

	public static function postJson($url, $param){
		$url .= (strpos($url, '?') !== false ? '&' : '?').'access_token='.static::getAccessToken();
		$param['access_token'] = self::getAccessToken();
		return parent::postJson($url, $param);
	}
}
