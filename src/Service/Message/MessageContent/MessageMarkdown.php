<?php
namespace LFPhp\WeworkSdk\Service\Message\MessageContent;

/**
 * @package LFPhp\WeworkSdk\Service\Message\MessageContent
 */
class MessageMarkdown extends MessagePrototype {
	public $content;

	public function getMessageType(){
		return 'markdown';
	}
}
