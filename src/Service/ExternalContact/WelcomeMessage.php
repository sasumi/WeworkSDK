<?php

namespace LFPhp\WeworkSdk\Service\ExternalContact;

use LFPhp\WeworkSdk\Base\AuthorizedService;

class WelcomeMessage extends AuthorizedService {
	/**
	 * 发送欢迎语
	 * @param $welcome_code
	 * @param $msg_info
	 * @return \LFPhp\WeworkSdk\Base\Response
	 * User: Richard
	 * @see https://open.work.weixin.qq.com/api/doc/90000/90135/92137
	 */
	public static function sendMessage($welcome_code, $msg_info){
		$url = '/cgi-bin/externalcontact/send_welcome_msg';
		$param = array_merge(['welcome_code' => $welcome_code], $msg_info);
		return self::postJson($url, $param);
	}
}
