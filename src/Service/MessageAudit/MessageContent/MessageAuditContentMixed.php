<?php

namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentMixed extends MessageAuditContentPrototype {
	public $action;//消息动作，目前有send(发送消息)/recall(撤回消息)/switch(切换企业日志)三种类型。String类型
	public $from;//消息发送方id。同一企业内容为userid，非相同企业为external_userid。消息如果是机器人发出，也为external_userid。String类型
	public $to_list;//消息接收方列表，可能是多个，同一个企业内容为userid，非相同企业为external_userid。数组，内容为string类型
	public $msg_time;//消息发送时间戳，utc时间,单位毫秒。

	public function __construct(array $data = []){
		if($data){
			$this->action = $data['meeting_voice_call']['action'];
			$this->from = $data['meeting_voice_call']['from'];
			$this->to_list = $data['meeting_voice_call']['tolist'];
			$this->msg_time = $data['meeting_voice_call']['msgtime'];
		}
		parent::__construct($data);
	}
}
