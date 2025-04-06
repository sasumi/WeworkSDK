<?php

namespace LFPhp\WeworkSdk\InternalAppEvent;

use LFPhp\WeworkSdk\Base\EventAbstract;
use LFPhp\WeworkSdk\InternalAppEvent\AddressBook\EventChangeContact;
use LFPhp\WeworkSdk\InternalAppEvent\ExternalContact\EventChangeExternalContact;

abstract class Resolver {
	protected $xml;

	const INFO_TYPE_MAP = [
		'change_contact'          => EventChangeContact::class,
		'change_external_contact' => EventChangeExternalContact::class,
	];

	/**
	 * @param $event_xml
	 * @return \LFPhp\WeworkSdk\Base\EventAbstract
	 * @throws \Exception
	 */
	public static function parser($event_xml){
		$xml = new \DOMDocument();
		$xml->loadXML($event_xml);
		$info_type_arr = $xml->getElementsByTagName('Event'); //内部的事件是放在Eve nt节点里面
		$info_type = $info_type_arr->item(0)->nodeValue;
		$class = self::INFO_TYPE_MAP[$info_type];
		return EventAbstract::resolveSubEvent($class, $event_xml);
	}
}
