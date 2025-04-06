<?php

namespace LFPhp\WeworkSdk\ExternalAppEvent\ExternalContact;

use LFPhp\WeworkSdk\ExternalAppEvent\EventAuthorized;

/**
 * 标签变更事件
 * https://open.work.weixin.qq.com/api/doc/90001/90143/92277#%E4%BC%81%E4%B8%9A%E5%AE%A2%E6%88%B7%E6%A0%87%E7%AD%BE%E5%88%9B%E5%BB%BA%E4%BA%8B%E4%BB%B6
 */
class EventChangeExternalTag extends EventAuthorized {
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
			'create' => EventCreateExternalTagContact::class,
			'update' => EventUpdateExternalTagContact::class,
			'delete' => EventDeleteExternalTagContact::class,
		];
		if($sub_event_map[$this->change_type]){
			$class = $sub_event_map[$this->change_type];
			return new $class($this->xml_string);
		}
		throw new \Exception('No sub event type found:'.$this->change_type);
	}
}
