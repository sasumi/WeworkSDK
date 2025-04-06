<?php

namespace LFPhp\WeworkSdk\ExternalAppEvent\AddressBook;

class EventDeleteUser extends EventChangeContact {
	public $user_id;
	public $create_time;

	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->user_id = $this->getValueByTagName('UserID');
		$this->create_time = $this->getValueByTagName('CreateTime');
	}
}
