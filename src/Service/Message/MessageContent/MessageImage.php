<?php
namespace LFPhp\WeworkSdk\Service\Message\MessageContent;

class MessageImage extends MessagePrototype {
	public $media_id; //图片媒体文件id，可以调用上传临时素材接口获取

	public function getMessageType(){
		return 'image';
	}
}
