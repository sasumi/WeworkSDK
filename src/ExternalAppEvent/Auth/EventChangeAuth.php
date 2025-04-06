<?php

namespace LFPhp\WeworkSdk\ExternalAppEvent\Auth;

use LFPhp\WeworkSdk\Base\EventAbstract;

/**
 * 成员通知事件，部门通知事件，标签成员变更事件
 * Class EventChangeContact
 * @package LFPhp\WeworkSdk\Event
 */
class EventChangeAuth extends EventAbstract {
	public $auth_corp_id;

	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->auth_corp_id = $this->getValueByTagName('AuthCorpId');
	}
}
