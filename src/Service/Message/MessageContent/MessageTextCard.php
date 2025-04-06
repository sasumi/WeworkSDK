<?php
namespace LFPhp\WeworkSdk\Service\Message\MessageContent;

class MessageTextCard extends MessagePrototype {
	public $title; //标题，不超过128个字节，超过会自动截断（支持id转译）
	public $description; //描述，不超过512个字节，超过会自动截断（支持id转译）
	public $url; //点击后跳转的链接。 最长2048字节，请确保包含了协议头(http/https)
	public $btntxt; //按钮文字。 默认为“详情”， 不超过4个文字，超过自动截断。

	public function getMessageType(){
		return 'textcard';
	}
}
