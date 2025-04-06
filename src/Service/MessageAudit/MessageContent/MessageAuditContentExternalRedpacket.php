<?php

namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentExternalRedpacket extends MessageAuditContentPrototype {
	const TYPE_NORMAL = 1; //普通红包
	const TYPE_GROUP_RANDOM = 2; //拼手气群红包
	const TYPE_GROUP_ACTIVE = 3; //激励群红包

	public $type; //红包消息类型。1 普通红包、2 拼手气群红包、3 激励群红包。Uint32类型
	public $wish; //红包祝福语。String类型
	public $total_count; //红包总个数。Uint32类型
	public $total_amount; //红包总金额。Uint32类型，单位为分。

	public function __construct(array $data = []){
		if($data){
			$this->type = $data['redpacket']['type'];
			$this->wish = $data['redpacket']['wish'];
			$this->total_count = $data['redpacket']['totalcnt'];
			$this->total_amount = $data['redpacket']['totalamount'];
			unset($data['redpacket']);
		}
		parent::__construct($data);
	}
}
