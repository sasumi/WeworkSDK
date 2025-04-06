<?php
namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentAgree extends MessageAuditContentPrototype {
	public $user_id; //同意/不同意协议者的userid，外部企业默认为external_userid。String类型
	public $agree_time;

	public function __construct(array $data = []){
		if($data){
			$this->user_id = $data['agree']['userid'];
			$this->agree_time = $data['agree']['agree_time'];
		}
		parent::__construct($data);
	}
}
