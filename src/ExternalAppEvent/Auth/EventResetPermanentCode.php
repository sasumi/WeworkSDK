<?php

namespace LFPhp\WeworkSdk\ExternalAppEvent\Auth;

use LFPhp\WeworkSdk\Base\EventAbstract;

class EventResetPermanentCode extends EventAbstract {
	public $auth_code;

	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->auth_code = $this->getValueByTagName('AuthCode');
	}
}
