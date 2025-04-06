<?php

namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentCard extends MessageAuditContentPrototype {
	public $corp_name; //名片所有者所在的公司名称。String类型
	public $user_id; //名片所有者的id，同一公司是userid，不同公司是external_userid。String类型

	public function __construct(array $data = []){
		if($data){
			$this->corp_name = $data['card']['corpname'];
			$this->user_id = $data['card']['userid'];
		}
		parent::__construct($data);
	}
}
