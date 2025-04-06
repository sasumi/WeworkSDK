<?php

namespace LFPhp\WeworkSdk\InternalAppEvent\ExternalContact;

use LFPhp\WeworkSdk\InternalAppEvent\EventAuthorized;

/**
 * 客户群变更事件
 * 仅企业自建应用可用
 */
class EventChangeExternalChat extends EventAuthorized {
	public $chat_id; //群ID

	/**
	 * 客户群变更事件()
	 * 客户群被修改后（群名变更，群成员增加或移除），回调该事件。收到该事件后，
	 * 新建群不会回调该时间，只能手动同步
	 * @see https://work.weixin.qq.com/api/doc/90000/90135/92130
	 * EventCreateExternalContact constructor.
	 * @param string $event_xml
	 */
	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->chat_id = $this->getValueByTagName('ChatId');
	}
}
