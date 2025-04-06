<?php

namespace LFPhp\WeworkSdk\ExternalAppEvent\Auth;

use LFPhp\WeworkSdk\Base\EventAbstract;

class EventSuiteTicket extends EventAbstract {
	public $suite_ticket;

	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->suite_ticket = $this->getValueByTagName('SuiteTicket');
	}
}
