<?php

namespace LFPhp\WeworkSdk\InternalAppEvent\AddressBook;

use LFPhp\WeworkSdk\InternalAppEvent\EventAuthorized;

/**
 * 成员通知事件，部门通知事件，标签成员变更事件
 * Class EventChangeContact
 * @package LFPhp\WeworkSdk\Event
 */
class EventChangeContact extends EventAuthorized {
	public $change_type;

	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->change_type = $this->getValueByTagName('ChangeType');
	}

	public function getSubEvent(){
		$sub_event_map = [
			'create_user'  => EventCreateUser::class,
			'update_user'  => EventUpdateUser::class,
			'delete_user'  => EventDeleteUser::class,
			'create_party' => EventCreateParty::class,
			'update_party' => EventUpdateParty::class,
			'delete_party' => EventDeleteParty::class,
			'update_tag'   => EventUpdateTag::class,
		];
		if($sub_event_map[$this->change_type]){
			$class = $sub_event_map[$this->change_type];
			return new $class($this->xml_string);
		}
		throw new \Exception('No sub event type found:'.$this->change_type);
	}
}
