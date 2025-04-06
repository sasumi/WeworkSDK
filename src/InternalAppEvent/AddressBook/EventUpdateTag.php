<?php

namespace LFPhp\WeworkSdk\InternalAppEvent\AddressBook;

/**
 * 标签变更事件
 */
class EventUpdateTag extends EventChangeContact {
	public $tag_id;
	public $add_user_list = [];
	public $del_user_list = [];
	public $add_party_list = [];
	public $del_party_list = [];

	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->tag_id = $this->getValueByTagName('TagId');
		$this->add_user_list = explode(',', $this->getValueByTagName('AddUserItems') ?: '');
		$this->del_user_list = explode(',', $this->getValueByTagName('DelUserItems') ?: '');
		$this->add_party_list = explode(',', $this->getValueByTagName('AddPartyItems') ?: '');
		$this->del_party_list = explode(',', $this->getValueByTagName('DelPartyItems') ?: '');
	}
}
