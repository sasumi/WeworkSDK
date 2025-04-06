<?php
namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentImage extends MessageAuditContentPrototype {
	public $sdk_file_id; //媒体资源的id信息。String类型
	public $md5sum; //资源的md5值，供进行校验。String类型
	public $file_size; //文件大小。Uint32类型

	public function __construct(array $data = []){
		if($data){
			$this->sdk_file_id = $data['image']['sdkfileid'];
			$this->md5sum = $data['image']['md5sum'];
			$this->file_size = $data['image']['filesize'];
		}
		parent::__construct($data);
	}
}
