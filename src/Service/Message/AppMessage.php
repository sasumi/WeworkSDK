<?php
namespace LFPhp\WeworkSdk\Service\Message;

use Exception;
use LFPhp\WeworkSdk\Base\AuthorizedService;
use LFPhp\WeworkSdk\Service\Message\MessageContent\MessagePrototype;

/**
 * 消息发送
 * @package LFPhp\WeworkSdk\Service\Message
 * @link https://open.work.weixin.qq.com/api/doc/90001/90143/90372
 */
class AppMessage extends AuthorizedService {
	const TO_ALL_RECEIVER = '@all';

	public $agent_id; //企业应用的id，整型。企业内部开发，可在应用的设置页面查看；第三方服务商，可通过接口 获取企业授权信息 获取该参数值
	public $safe_flag = 0; //表示是否是保密消息，0表示可对外分享，1表示不能分享且内容显示水印，默认为0
	public $enable_id_trans = 0; //表示是否开启id转译，0表示否，1表示是，默认0。仅第三方应用需要用到，企业自建应用可以忽略。
	public $enable_duplicate_check = 0; //表示是否开启重复消息检查，0表示否，1表示是，默认为0
	public $duplicate_check_interval = 1800; //表示是否重复消息检查的时间间隔，默认1800s，最大不超过4小时

	/**
	 * AppMessage constructor.
	 */
	public function __construct(){
	}

	/**
	 * @return \LFPhp\WeworkSdk\Service\Message\AppMessage
	 */
	public static function instance(){
		static $instance;
		if(!$instance){
			$instance = new self();
		}
		return $instance;
	}

	/**
	 * 发送消息
	 * @param MessagePrototype $content
	 * @param string[] $to_users
	 * @param string[] $to_parties
	 * @param string[] $to_tags
	 * @return array [无效用户ID列表, 无效部门列表, 无效标签列表
	 * @throws \\Exception
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 */
	public function sendMessage(MessagePrototype $content, array $to_users = [], array $to_parties = [], array $to_tags = []){
		$uri = "/cgi-bin/message/send";
		if(!$to_parties && !$to_tags && !$to_users){
			throw new Exception('接收人为空，请指定成员ID、部门或标签');
		}
		$content_type = $content->getMessageType();
		$param = [
			"msgtype"                  => $content_type,
			"agentid"                  => $this->agent_id,
			"safe"                     => $this->safe_flag,
			"enable_id_trans"          => $this->enable_id_trans,
			"enable_duplicate_check"   => $this->enable_duplicate_check,
			"duplicate_check_interval" => $this->duplicate_check_interval,
		];
		if($to_users){
			$param['touser'] = implode('|', $to_users);
		}
		if($to_parties){
			$param['toparty'] = implode('|', $to_parties);
		}
		if($to_tags){
			$param['totag'] = implode('|', $to_tags);
		}
		$param[$content_type] = $content->toArray();
		$rsp = self::sendRequest($uri, $param);
		$rsp->assertSuccess();
		$invalid_users = $rsp->get('invaliduser') ? explode('|', $rsp->get('invaliduser')) : [];
		$invalid_parties = $rsp->get('invalidparty') ? explode('|', $rsp->get('invalidparty')) : [];
		$invalid_tags = $rsp->get('invalidtag') ? explode('|', $rsp->get('invalidtag')) : [];
		return [$invalid_users, $invalid_parties, $invalid_tags];
	}
}
