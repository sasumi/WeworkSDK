<?php

namespace LFPhp\WeworkSdk\ExternalAppEvent\ExternalContact;

class EventCreateExternalContactChat extends EventChangeExternalChat {

	/**
	 * EventCreateExternalContact constructor.
	 * @param string $event_xml
	 */
	public function __construct($event_xml){
		parent::__construct($event_xml);
	}
}
