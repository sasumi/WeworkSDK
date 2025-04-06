<?php

namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentLocation extends MessageAuditContentPrototype {
	public $longitude; //经度，单位double
	public $latitude; //纬度，单位double
	public $address; //地址信息。String类型
	public $title; //位置信息的title。String类型
	public $zoom; //缩放比例。Uint32类型

	public function __construct(array $data = []){
		if($data){
			$this->longitude = $data['location']['longitude'];
			$this->latitude = $data['location']['latitude'];
			$this->address = $data['location']['address'];
			$this->title = $data['location']['title'];
			$this->zoom = $data['location']['zoom'];
		}
		parent::__construct($data);
	}
}
