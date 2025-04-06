<?php

namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentTodo extends MessageAuditContentPrototype {
	public $title;
	public $content;

	public function __construct(array $data = []){
		if($data){
			$this->title = $data['todo']['title'];
			$this->content = $data['todo']['content'];
		}
		parent::__construct($data);
	}
}
