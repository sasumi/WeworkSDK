<?php

namespace LFPhp\WeworkSdk\Base;

use LFPhp\Logger\Logger;

abstract class Service {
	const WEWORK_HOST = 'https://qyapi.weixin.qq.com';
	const DEFAULT_TIMEOUT = 20;

	/**
	 * @param $url
	 * @param array $param
	 * @param bool $in_post
	 * @param array $files
	 * @return \LFPhp\WeworkSdk\Base\Response
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	protected static function sendRequest($url, array $param = [], $in_post = true, $files = []){
		$request = Request::instance([
			'host'    => self::WEWORK_HOST,
			'timeout' => self::DEFAULT_TIMEOUT,
		]);

		$logger = Logger::instance(__CLASS__);
		$rsp = $request->sendInJson($url, $param, $in_post, $files);

		//解析企业微信统一返回结果格式标准，如有变更，需要考虑是否在子类中实现
		//企业微信接口有时候没有errorcode, 例如get_suite_access_token
		//https://work.weixin.qq.com/api/doc/90001/90143/90600
		if(isset($rsp['errcode']) && $rsp['errcode'] == 0){
			return Response::success($rsp['errmsg'] ?: 'Success', $rsp);
		}
		if(!isset($rsp['errcode'])){
			return Response::success('Success', $rsp);
		}

		[$friendly_msg, $tech_msg] = ReturnCode::getMessages($rsp['errcode']);
		if(!$friendly_msg){
			$friendly_msg = $rsp['errmsg'];
		}

		$logger->error('Service response error', $rsp, $tech_msg);
		return Response::error($friendly_msg, array_merge($rsp, ['tech_msg' => $tech_msg]), $rsp['errcode']);
	}

}
