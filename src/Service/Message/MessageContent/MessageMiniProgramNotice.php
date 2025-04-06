<?php
namespace LFPhp\WeworkSdk\Service\Message\MessageContent;

/**
 * @package LFPhp\WeworkSdk\Service\Message\MessageContent
 */
class MessageMiniProgramNotice extends MessagePrototype {
	public $appid; //小程序appid，必须是与当前小程序应用关联的小程序
	public $page; //点击消息卡片后的小程序页面，仅限本小程序内的页面。该字段不填则消息点击后不跳转。
	public $title; //消息标题，长度限制4-12个汉字（支持id转译）
	public $description; //消息描述，长度限制4-12个汉字（支持id转译）
	public $emphasis_first_item = true;
	public $content_item = [ //消息内容键值对，最多允许10个item
		/**
		 * {
		 * "key": "会议室",
		 * "value": "402"
		 * },
		 */
	];

	public function getMessageType(){
		return 'miniprogram_notice';
	}
}
