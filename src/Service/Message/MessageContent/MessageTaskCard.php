<?php
namespace LFPhp\WeworkSdk\Service\Message\MessageContent;

/**
 * @package LFPhp\WeworkSdk\Service\Message\MessageContent
 */
class MessageTaskCard extends MessagePrototype {
	public $title; //标题，不超过128个字节，超过会自动截断（支持id转译）
	public $description; //描述，不超过512个字节，超过会自动截断（支持id转译）
	public $url; //点击后跳转的链接。最长2048字节，请确保包含了协议头(http/https)
	public $task_id; //任务id，同一个应用发送的任务卡片消息的任务id不能重复，只能由数字、字母和“_-@”组成，最长支持128字节
	public $btn = [/**按钮列表，按钮个数为1~2个。
	 * {
	 * "key": "key111", //按钮key值，用户点击后，会产生任务卡片回调事件，回调事件会带上该key值，只能由数字、字母和“_-@”组成，最长支持128字节
	 * "name": "批准", //按钮名称
	 * "replace_name": "已批准", //点击按钮后显示的名称，默认为“已处理”
	 * "color":"red", //按钮字体颜色，可选“red”或者“blue”,默认为“blue”
	 * "is_bold": true //按钮字体是否加粗，默认false
	 * },
	 **/
	];

	public function getMessageType(){
		return 'taskcard';
	}
}
