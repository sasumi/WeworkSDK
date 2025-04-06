<?php

namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentChatRecord extends MessageAuditContentPrototype {
	public $title; //聊天记录标题。String类型
	public $items = []; //消息记录内的消息内容，批量数据，格式为：{"type":"ChatRecordText","msgtime":1603875610,"content":"{\"content\":\"test\"}","from_chatroom":false}

	public function __construct(array $data = []){
		if($data){
			$this->title = $data['chatrecord']['title'];
			$this->items = $data['chatrecord']['item'];
		}
		parent::__construct($data);
	}
}
