<?php
namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentRevoke extends MessageAuditContentPrototype {
	public $pre_msg_id;

	public function __construct(array $data = []){
		if($data){
			$this->pre_msg_id = $data['revoke']['pre_msgid'];
		}
		parent::__construct($data);
	}
}
