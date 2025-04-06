<?php

namespace LFPhp\WeworkSdk\ExternalAppEvent\AddressBook;

class EventUpdateParty extends EventChangeContact {
	public $department_id;
	public $name;
	public $parent_id;

	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->department_id = $this->getValueByTagName('Id');
		$this->name = $this->getValueByTagName('Name');
		$this->parent_id = $this->getValueByTagName('ParentId');
	}
}
