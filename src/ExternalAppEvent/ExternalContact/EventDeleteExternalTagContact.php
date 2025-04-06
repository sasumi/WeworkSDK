<?php

namespace LFPhp\WeworkSdk\ExternalAppEvent\ExternalContact;

class EventDeleteExternalTagContact extends EventChangeExternalTag {
	public $tag_id; //标签或标签组的ID
	public $tag_type; //创建标签时，此项为tag，创建标签组时，此项为tag_group
	public $timestamp; //unix时间戳

	/**
	 * EventCreateExternalContact constructor.
	 * @param string $event_xml
	 */
	public function __construct($event_xml){
		parent::__construct($event_xml);
		$this->tag_id = $this->getValueByTagName('Id');
		$this->tag_type = $this->getValueByTagName('TagType');
		$this->timestamp = $this->getValueByTagName('TimeStamp');
	}

}
