<?php

namespace LFPhp\WeworkSdk\Service\Message\MessageContent;

use LFPhp\WeworkSdk\Base\DataPrototype;

abstract class MessagePrototype extends DataPrototype {
	/**
	 * @return string
	 */
	abstract public function getMessageType();
}
