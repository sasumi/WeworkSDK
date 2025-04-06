<?php
namespace LFPhp\WeworkSdk\InternalAppEvent\ExternalContact;

/**
 * 删除外部联系人事件
 */
class EventDeleteExternalContact extends EventChangeExternalContact {
	public $user_id; //企业服务人员的UserID
	public $external_user_id; //外部联系人的userid，注意不是企业成员的帐号

	/**
	 * EventCreateExternalContact constructor.
	 * @param $event_xml
	 */
	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->user_id = $this->getValueByTagName('UserID');
		$this->external_user_id = $this->getValueByTagName('ExternalUserID');
	}
}
