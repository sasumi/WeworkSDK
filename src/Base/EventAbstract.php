<?php
namespace LFPhp\WeworkSdk\Base;

use DOMDocument;
use LFPhp\WeworkSdk\Exception\EventNoSupportException;

/**
 * 企业微信事件父类
 */
class EventAbstract {
	protected $xml;
	protected $xml_string;

	public function __construct($event_xml){
		$this->xml_string = $event_xml;
		$this->xml = new DOMDocument();
		$this->xml->loadXML($event_xml);
	}

	/**
	 * 获取tag下面的node value
	 * @param $tag_name
	 * @param \DOMDocument|null $xml
	 * @return string|null
	 */
	public function getValueByTagName($tag_name, DOMDocument $xml = null){
		$item = ($xml ?: $this->xml)->getElementsByTagName($tag_name)->item(0);
		if(!$item){
			return null;
		}
		return $item->nodeValue;
	}

	/**
	 * @param $class
	 * @param $event_xml
	 * @return \LFPhp\WeworkSdk\Base\EventAbstract
	 * @throws \ReflectionException
	 * @throws \LFPhp\WeworkSdk\Exception\EventNoSupportException
	 */
	final public static function resolveSubEvent($class, $event_xml){
		if(!isset($class) || !class_exists($class)){
			throw new EventNoSupportException('No event type resolved:'.$class);
		}

		/** @var \LFPhp\WeworkSdk\Base\EventAbstract $event */
		$event = new $class($event_xml);
		$sub_event = null;
		while($sub_event = $event->getSubEvent()){
			$method = (new \ReflectionClass($sub_event))->getMethod('getSubEvent');
			//确保getSubEvent必须是子类的方法
			if(!$method || $method->getDeclaringClass()->getName() != get_class($sub_event)){
				break;
			}
		}
		return $sub_event ?: $event;
	}

	public function getSubEvent(){
		return null;
	}
}
