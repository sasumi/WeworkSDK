<?php

namespace LFPhp\WeworkSdk\InternalAppEvent\AddressBook;

class EventCreateParty extends EventChangeContact {
	public $department_id;
	public $name;
	public $parent_id;
	public $order;

	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->department_id = $this->getValueByTagName('Id');
		$this->name = $this->getValueByTagName('Name');
		$this->parent_id = $this->getValueByTagName('ParentId');
		$this->order = $this->getValueByTagName('Order');
	}
}
