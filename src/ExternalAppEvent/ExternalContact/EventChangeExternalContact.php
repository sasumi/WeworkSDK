<?php

namespace LFPhp\WeworkSdk\ExternalAppEvent\ExternalContact;

use LFPhp\WeworkSdk\ExternalAppEvent\EventAuthorized;

/**
 * 成员通知事件，部门通知事件，标签成员变更事件
 */
class EventChangeExternalContact extends EventAuthorized {
	public $change_type;

	/**
	 * EventChangeExternalContact constructor.
	 * @param string $event_xml
	 */
	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->change_type = $this->getValueByTagName('ChangeType');
	}

	/**
	 * @return static
	 * @throws \Exception
	 */
	public function getSubEvent(){
		$sub_event_map = [
			'add_external_contact'  => EventCreateExternalContact::class,
			'edit_external_contact' => EventUpdateExternalContact::class,
			'del_external_contact'  => EventDeleteExternalContact::class,
			'del_follow_user'       => EventDeleteFollowUser::class,
			'change_external_chat'  => EventChangeExternalChat::class,
			'msg_audit_approved'    => EventMsgAuditApproved::class,
		];
		if($sub_event_map[$this->change_type]){
			$class = $sub_event_map[$this->change_type];
			return new $class($this->xml_string);
		}
		throw new \Exception('No sub event type found:'.$this->change_type);
	}
}
