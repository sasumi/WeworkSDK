<?php
namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentText extends MessageAuditContentPrototype {
	public $content; //消息内容

	public function __construct(array $data = []){
		if($data){
			$this->content = $data['text']['content'];
		}
		parent::__construct($data);
	}
}
