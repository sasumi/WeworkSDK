<?php

namespace LFPhp\WeworkSdk\InternalAppEvent\AddressBook;

class EventDeleteUser extends EventChangeContact {
	public $user_id;

	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->user_id = $this->getValueByTagName('UserID');
	}
}
