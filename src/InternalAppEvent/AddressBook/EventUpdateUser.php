<?php

namespace LFPhp\WeworkSdk\InternalAppEvent\AddressBook;

class EventUpdateUser extends EventChangeContact {
	public $user_id;
	public $new_user_id;

	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->user_id = $this->getValueByTagName('UserID');
		$this->new_user_id = $this->getValueByTagName('NewUserID');
	}
}
