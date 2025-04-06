<?php
namespace LFPhp\WeworkSdk\ExternalAppEvent\CorpMember;

use LFPhp\WeworkSdk\Base\EventAbstract;

/**
 * 成员关注及取消关注事件
 */
class Subscribe extends EventAbstract {
	public $auth_corp_id; //企业微信CorpID
	public $corp_user_id; //成员UserID，是企业员工

	/**
	 * EventCreateExternalContact constructor.
	 * @param $event_xml
	 */
	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->auth_corp_id = $this->getValueByTagName('ToUserName');
		$this->corp_user_id = $this->getValueByTagName('FromUserName');
	}
}
