<?php

namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentMarkdown extends MessageAuditContentPrototype {
	public $content;

	public function __construct(array $data = []){
		if($data){
			$this->content = $data['markdown']['content'];
		}
		parent::__construct($data);
	}
}
