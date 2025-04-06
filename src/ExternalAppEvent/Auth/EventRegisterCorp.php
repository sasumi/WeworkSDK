<?php

namespace LFPhp\WeworkSdk\ExternalAppEvent\Auth;

use LFPhp\WeworkSdk\Base\EventAbstract;

class EventRegisterCorp extends EventAbstract {
	public $auth_corp_id;
	public $user_id; //企业服务人员的UserID
	public $state; //来源渠道
	public $register_code; //注册码
	public $access_token; //调用凭证
	public $expires_in; //过期时间

	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->auth_corp_id = $this->getValueByTagName('AuthCorpId');
		$this->state = $this->getValueByTagName('State');
		$this->user_id = $this->getValueByTagName('UserId');
		$this->register_code = $this->getValueByTagName('RegisterCode');
		$this->access_token = $this->getValueByTagName('AccessToken');
		$this->expires_in = $this->getValueByTagName('ExpiresIn');
	}
}
