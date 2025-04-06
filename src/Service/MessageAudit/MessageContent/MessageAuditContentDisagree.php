<?php
namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentDisagree extends MessageAuditContentPrototype {
	public $userid; //同意/不同意协议者的userid，外部企业默认为external_userid。String类型
	public $agree_time; //同意/不同意协议的时间，utc时间，ms单位。

	public function __construct(array $data = []){
		if($data){
			$this->userid = $data['disagree']['userid'];
			$this->agree_time = $data['disagree']['agree_time'];
		}
		parent::__construct($data);
	}
}
