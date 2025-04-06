<?php
namespace LFPhp\WeworkSdk\Service\Message\MessageContent;

class MessageFile extends MessagePrototype {
	public $media_id; //文件id，可以调用上传临时素材接口获取

	public function getMessageType(){
		return 'file';
	}
}
