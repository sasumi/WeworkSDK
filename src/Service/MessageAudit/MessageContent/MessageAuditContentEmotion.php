<?php

namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentEmotion extends MessageAuditContentPrototype {
	const TYPE_GIF = 1;
	const TYPE_PNG = 2;

	public $type; //表情类型，png或者gif.1表示gif 2表示png。Uint32类型
	public $width; //表情图片宽度。Uint32类型
	public $height; //表情图片高度。Uint32类型
	public $sdk_file_id; //媒体资源的id信息。String类型
	public $md5sum; //资源的md5值，供进行校验。String类型
	public $image_size; //资源的文件大小。Uint32类型

	public function __construct(array $data = []){
		if($data){
			$this->type = $data['emotion']['type'];
			$this->width = $data['emotion']['width'];
			$this->height = $data['emotion']['height'];
			$this->sdk_file_id = $data['emotion']['sdkfileid'];
			$this->md5sum = $data['emotion']['md5sum'];
			$this->image_size = $data['emotion']['imagesize'];
		}
		parent::__construct($data);
	}
}
