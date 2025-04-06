<?php

namespace LFPhp\WeworkSdk\ExternalAppEvent;

use LFPhp\WeworkSdk\Base\EventAbstract;

/**
 * 已经授权的事件
 * Class EventAuthorized
 * @package LFPhp\WeworkSdk\Event
 */
class EventAuthorized extends EventAbstract {
	//套件（应用）ID
	public $suite_id;

	//企业ID
	public $auth_corp_id;

	//事件类型
	public $info_type;

	//事件发送事件
	public $timestamp;

	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->suite_id = $this->getValueByTagName('SuiteId');

		$this->auth_corp_id = $this->getValueByTagName('AuthCorpId');
		$this->info_type = $this->getValueByTagName('InfoType');
		$this->timestamp = $this->getValueByTagName('TimeStamp');
	}
}
