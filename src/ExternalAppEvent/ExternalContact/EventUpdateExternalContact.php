<?php

namespace LFPhp\WeworkSdk\ExternalAppEvent\ExternalContact;

/**
 * 更新外部联系人事件|编辑企业客户事件
 */
class EventUpdateExternalContact extends EventChangeExternalContact {
	public $user_id; //企业服务人员的UserID
	public $external_user_id; //外部联系人的userid，注意不是企业成员的帐号
	public $welcome_code; //欢迎语code，可用于发送欢迎语
	public $state;

	/**
	 * 外部联系人事件
	 * @see https://open.work.weixin.qq.com/api/doc/90000/90135/92130
	 * @see https://work.weixin.qq.com/api/doc/90001/90143/92277
	 * EventCreateExternalContact constructor.
	 * @param string $event_xml
	 */
	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->user_id = $this->getValueByTagName('UserID');
		$this->external_user_id = $this->getValueByTagName('ExternalUserID');
		$this->welcome_code = $this->getValueByTagName('WelcomeCode');
		$this->state = $this->getValueByTagName('State');
	}
}
