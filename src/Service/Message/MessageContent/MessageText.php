<?php
namespace LFPhp\WeworkSdk\Service\Message\MessageContent;

class MessageText extends MessagePrototype {
	public $content; //消息内容，最长不超过2048个字节，超过将截断（支持id转译）

	public function getMessageType(){
		return 'text';
	}
}
