<?php

namespace LFPhp\WeworkSdk\ExternalAppEvent;

use Exception;
use LFPhp\WeworkSdk\Base\EventAbstract;
use LFPhp\WeworkSdk\ExternalAppEvent\AddressBook\EventChangeContact;
use LFPhp\WeworkSdk\ExternalAppEvent\Auth\EventCancelAuth;
use LFPhp\WeworkSdk\ExternalAppEvent\Auth\EventChangeAuth;
use LFPhp\WeworkSdk\ExternalAppEvent\Auth\EventCreateAuth;
use LFPhp\WeworkSdk\ExternalAppEvent\Auth\EventRegisterCorp;
use LFPhp\WeworkSdk\ExternalAppEvent\Auth\EventResetPermanentCode;
use LFPhp\WeworkSdk\ExternalAppEvent\Auth\EventSuiteTicket;
use LFPhp\WeworkSdk\ExternalAppEvent\CorpMember\Subscribe;
use LFPhp\WeworkSdk\ExternalAppEvent\CorpMember\Unsubscribe;
use LFPhp\WeworkSdk\ExternalAppEvent\ExternalContact\EventChangeExternalChat;
use LFPhp\WeworkSdk\ExternalAppEvent\ExternalContact\EventChangeExternalContact;
use LFPhp\WeworkSdk\ExternalAppEvent\ExternalContact\EventChangeExternalTag;

abstract class Resolver {
	protected $xml;

	const INFO_TYPE_MAP = [
		'suite_ticket'            => EventSuiteTicket::class,
		'create_auth'             => EventCreateAuth::class,
		'change_auth'             => EventChangeAuth::class,
		'cancel_auth'             => EventCancelAuth::class,
		'change_contact'          => EventChangeContact::class,
		'change_external_contact' => EventChangeExternalContact::class,
		'change_external_chat'    => EventChangeExternalChat::class,
		'register_corp'           => EventRegisterCorp::class,
		'change_external_tag'     => EventChangeExternalTag::class,
		'reset_permanent_code'    => EventResetPermanentCode::class,
	];

	//没有subevent事件的类
	const SUBSCRIBE_EVENT_MAP = [
		'subscribe'               => Subscribe::class,
		'unsubscribe'             => Unsubscribe::class,
		'change_external_contact' => EventChangeExternalContact::class,
		'change_external_chat'    => EventChangeExternalChat::class,
	];

	/**
	 * @param $event_xml
	 * @return EventAbstract
	 * @throws \Exception
	 */
	public static function parser($event_xml){
		$xml = new \DOMDocument();
		$xml->loadXML($event_xml);
		$info_type_arr = $xml->getElementsByTagName('InfoType'); //外部事件回调是类型是放在InfoType节点里面
		if($info_type_arr->length != 0){
			$info_type = $info_type_arr->item(0)->nodeValue;
			$class = self::INFO_TYPE_MAP[$info_type];
			return EventAbstract::resolveSubEvent($class, $event_xml);
		}
		$info_type_arr = $xml->getElementsByTagName('Event');
		if($info_type_arr->length != 0){
			//部分回调事件没有放在InfoType，也没有subEvnet，只有Event
			$info_type_arr = $xml->getElementsByTagName('Event');
			//回调事件格式不一样，如何做区分处理
			$event_type = $info_type_arr->item(0)->nodeValue;
			$class = self::SUBSCRIBE_EVENT_MAP[$event_type];
			return EventAbstract::resolveSubEvent($class, $event_xml);
		}
		throw new Exception('not found event');
	}
}
