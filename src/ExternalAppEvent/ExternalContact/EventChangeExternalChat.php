<?php

namespace LFPhp\WeworkSdk\ExternalAppEvent\ExternalContact;

use LFPhp\WeworkSdk\ExternalAppEvent\EventAuthorized;

/**
 * 客户群变更事件
 * 仅企业自建应用可用
 */
class EventChangeExternalChat extends EventAuthorized {
	public $chat_id; //群ID
	public $change_type; //变更类型

	/**
	 * 客户群变更事件
	 * @see https://work.weixin.qq.com/api/doc/90000/90135/92130
	 * EventCreateExternalContact constructor.
	 * @param string $event_xml
	 */
	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->chat_id = $this->getValueByTagName('ChatId');
		$this->change_type = $this->getValueByTagName('ChangeType');
	}

	/**@return static
	 * @throws \Exception
	 * @see
	 * https://open.work.weixin.qq.com/api/doc/90001/90143/92277#%E5%AE%A2%E6%88%B7%E7%BE%A4%E5%88%9B%E5%BB%BA%E4%BA%8B%E4%BB%B6
	 */
	public function getSubEvent(){
		$sub_event_map = [
			'create'  => EventCreateExternalContactChat::class,
			'update'  => EventUpdateExternalContactChat::class,
			'dismiss' => EventDismissExternalContactChat::class,
		];
		if($sub_event_map[$this->change_type]){
			$class = $sub_event_map[$this->change_type];
			return new $class($this->xml_string);
		}
		throw new \Exception('No sub event type found:'.$this->change_type);
	}
}
