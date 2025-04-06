<?php

namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentWeapp extends MessageAuditContentPrototype {
	public $title;//消息标题。String类型
	public $description;//消息描述。String类型
	public $username;//用户名称。String类型
	public $display_name;//小程序名称。String类型

	public function __construct(array $data = []){
		$this->title = $data['weapp']['title'];
		$this->description = $data['weapp']['description'];
		$this->username = $data['weapp']['username'];
		$this->display_name = $data['weapp']['displayname'];
		parent::__construct($data);
	}
}
