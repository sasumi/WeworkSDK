<?php

namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentLink extends MessageAuditContentPrototype {
	public $title; //消息标题。String类型
	public $description; //消息描述。String类型
	public $link_url; //链接url地址。String类型
	public $image_url; //链接图片url。String类型

	public function __construct(array $data = []){
		//		if($data){
		//			$this->title; //消息标题。String类型
		//			$this->description; //消息描述。String类型
		//			$this->link_url; //链接url地址。String类型
		//			$this->image_url; //链接图片url。String类型
		//		}
		parent::__construct($data);
	}
}
