<?php
namespace LFPhp\WeworkSdk\Service\Message\MessageContent;

class MessageVideo extends MessagePrototype {
	public $media_id; //视频媒体文件id，可以调用上传临时素材接口获取
	public $title; //视频消息的标题，不超过128个字节，超过会自动截断
	public $description; //视频消息的描述，不超过512个字节，超过会自动截断

	public function getMessageType(){
		return 'video';
	}
}
