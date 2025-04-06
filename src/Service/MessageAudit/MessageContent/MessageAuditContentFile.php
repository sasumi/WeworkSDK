<?php

namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentFile extends MessageAuditContentPrototype {
	public $sdk_file_id; //媒体资源的id信息。String类型
	public $md5sum; //资源的md5值，供进行校验。String类型
	public $file_name; //文件名称。String类型
	public $file_ext; //文件类型后缀。String类型
	public $file_size; //文件大小。Uint32类型

	public function __construct(array $data = []){
		if($data){
			$this->sdk_file_id = $data['file']['sdkfileid'];
			$this->md5sum = $data['file']['md5sum'];
			$this->file_name = $data['file']['filename'];
			$this->file_ext = $data['file']['fileext'];
			$this->file_size = $data['file']['filesize'];
		}
		parent::__construct($data);
	}
}
