<?php

namespace LFPhp\WeworkSdk\ExternalAppEvent\Auth;

use LFPhp\WeworkSdk\Base\EventAbstract;

class EventCancelAuth extends EventAbstract {
	public $auth_corp_id;

	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->auth_corp_id = $this->getValueByTagName('AuthCorpId');
	}
}
