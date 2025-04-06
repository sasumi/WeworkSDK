<?php

namespace LFPhp\WeworkSdk\InternalAppEvent;

use LFPhp\WeworkSdk\Base\EventAbstract;

/**
 * 已经授权的事件
 * Class EventAuthorized
 * @package LFPhp\WeworkSdk\Event
 */
class EventAuthorized extends EventAbstract {
	//企业ID
	public $auth_corp_id;

	//事件类型
	public $info_type;

	//事件发送事件
	public $timestamp;

	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->auth_corp_id = $this->getValueByTagName('ToUserName');
		$this->info_type = $this->getValueByTagName('Event');
		$this->timestamp = $this->getValueByTagName('CreateTime');
	}
}
