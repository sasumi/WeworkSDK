<?php
namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentVoice extends MessageAuditContentPrototype {
	public $md5sum;
	public $file_size;
	public $sdk_file_id;
	public $play_length;

	public function __construct(array $data = []){
		if($data){
			$this->md5sum = $data['image']['md5sum'];
			$this->file_size = $data['image']['filesize'];
			$this->sdk_file_id = $data['image']['sdkfileid'];
			$this->play_length = $data['image']['play_length'];
		}
		parent::__construct($data);
	}
}
