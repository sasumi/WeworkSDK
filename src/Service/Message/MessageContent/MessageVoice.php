<?php
namespace LFPhp\WeworkSdk\Service\Message\MessageContent;

class MessageVoice extends MessagePrototype {
	public $media_id;

	public function getMessageType(){
		return 'voice';
	}
}
