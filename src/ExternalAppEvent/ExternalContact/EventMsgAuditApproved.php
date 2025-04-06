<?php
namespace LFPhp\WeworkSdk\ExternalAppEvent\ExternalContact;

/**
 * 客户同意进行聊天内容存档事件回调
 */
class EventMsgAuditApproved extends EventChangeExternalContact {
	public $corp_id; //企业微信CorpID
	public $from_user; //此事件该值固定为sys，表示该消息由系统生成
	public $agree_time; //消息创建时间 （整型）
	public $corp_user_id; //企业服务人员的UserID
	public $external_user_id; //外部联系人的userid，注意不是企业成员的帐号
	public $welcome_code; //外部联系人的userid，注意不是企业成员的帐号

	/**
	 * EventCreateExternalContact constructor.
	 * @param string $event_xml
	 */
	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->corp_id = $this->getValueByTagName('ToUserName');
		$this->from_user = $this->getValueByTagName('FromUserName');
		$this->agree_time = $this->getValueByTagName('CreateTime');
		$this->corp_user_id = $this->getValueByTagName('UserID');
		$this->external_user_id = $this->getValueByTagName('ExternalUserID');
		$this->welcome_code = $this->getValueByTagName('WelcomeCode');
	}
}
