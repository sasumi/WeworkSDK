<?php
namespace LFPhp\WeworkSdk\Service;

use LFPhp\Logger\Logger;
use LFPhp\WeworkSdk\Base\AuthorizedService;

class JSApi extends AuthorizedService {
	const TYPE_APP = 'app';
	const TYPE_CORP = 'corp';

	/**
	 * 获取JS所使用的signature
	 * @param string $page_url 当前网页URL
	 * @param string $type
	 * @param callable $js_api_ticket_cache_handler 缓存函数
	 * @return array [$encoded_str, $nonce_str, $timestamp]
	 */
	public static function getJsSDKToken($page_url, $type, callable $js_api_ticket_cache_handler){
		if(!$page_url){
			throw new \Exception('Page url required');
		}
		$logger = Logger::instance(__CLASS__);
		$nonce_str = substr(md5(time().rand()), 0, 16);
		switch($type){
			case self::TYPE_APP:
				$js_api_ticket = self::getAppJsApiTicket($js_api_ticket_cache_handler);
				break;
			case self::TYPE_CORP:
				$js_api_ticket = self::getCorpJsApiTicket($js_api_ticket_cache_handler);
				break;
			default:
				throw new \Exception('Type required:'.$type);
		}

		$timestamp = time();
		$str = "jsapi_ticket=$js_api_ticket&noncestr=$nonce_str&timestamp=$timestamp&url=$page_url";
		$logger->info(__METHOD__, 'before encode', $str);
		$encoded_str = sha1($str);
		$logger->info('after encoded sha1:', $encoded_str);
		return [$encoded_str, $nonce_str, $timestamp];
	}

	/**
	 * 获取企业js api ticket
	 * 生成签名之前必须先了解一下jsapi_ticket，
	 * jsapi_ticket是H5应用调用企业微信JS接口的临时票据。
	 * 正常情况下，jsapi_ticket的有效期为7200秒，通过access_token来获取。
	 * 由于获取jsapi_ticket的api调用次数非常有限（一小时内，一个企业最多可获取400次，且单个应用不能超过100次），
	 * 频繁刷新jsapi_ticket会导致api调用受限，影响自身业务，开发者必须在自己的服务全局缓存jsapi_ticket。
	 * @param callable $js_api_ticket_cache_handler js ticket 缓存函数（cache_key，cache_data=null, seconds)
	 * @return string
	 */
	public static function getCorpJsApiTicket(callable $js_api_ticket_cache_handler){
		$url = '/cgi-bin/get_jsapi_ticket';
		$access_token = self::getAccessToken();
		$logger = Logger::instance(__CLASS__);

		if($ticket = $js_api_ticket_cache_handler($access_token)){
			return $ticket;
		}
		$rsp = self::sendRequest($url, [], false);
		$rsp->assertSuccess();
		$ticket = $rsp->get('ticket');
		$expires_in = $rsp->get('expires_in');
		$js_api_ticket_cache_handler($access_token, $ticket, $expires_in);
		return $ticket;
	}

	/**
	 * 获取应用的jsapi_ticket
	 * 签名的jsapi_ticket必须使用以下接口获取。且必须用wx.agentConfig中的agentid对应的应用secret去获取access_token。
	 * @param callable $js_api_ticket_cache_handler js ticket 缓存函数（cache_key，cache_data=null, seconds)
	 * @return string
	 * @see https://open.work.weixin.qq.com/api/doc/90001/90144/90539
	 */
	public static function getAppJsApiTicket(callable $js_api_ticket_cache_handler){
		$url = '/cgi-bin/ticket/get';
		$access_token = self::getAccessToken();
		$logger = Logger::instance(__CLASS__);

		if($ticket = $js_api_ticket_cache_handler($access_token)){
			$logger->info('get_jsapi_ticket_app from cache:', $ticket);
			return $ticket;
		}
		$rsp = self::sendRequest($url, ['type' => 'agent_config'], false);
		$rsp->assertSuccess();
		$ticket = $rsp->get('ticket');
		$expires_in = $rsp->get('expires_in');
		$js_api_ticket_cache_handler($access_token, $ticket, $expires_in);
		$logger->info("jsapi_debug", "get_app_ticket_get:".$rsp->toJSON());
		$logger->info('get_jsapi_ticket_app rsp', $rsp->toJSON());
		return $ticket;
	}
}
