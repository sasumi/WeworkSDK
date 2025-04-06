<?php

namespace LFPhp\WeworkSdk\ExternalAppEvent\AddressBook;

class EventDeleteParty extends EventChangeContact {
	public $department_id;

	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->department_id = $this->getValueByTagName('Id');
	}
}
