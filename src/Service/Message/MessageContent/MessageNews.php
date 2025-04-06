<?php
namespace LFPhp\WeworkSdk\Service\Message\MessageContent;

/**
 * 图文消息
 * Class MessageImageText
 * @package LFPhp\WeworkSdk\Service\Message\MessageContent
 */
class MessageNews extends MessagePrototype {
	public $articles = [
		//		 {
		//            "title" : "中秋节礼品领取",
		//            "description" : "今年中秋节公司有豪礼相送",
		//            "url" : "URL",
		//            "picurl" : "http://res.mail.qq.com/node/ww/wwopenmng/images/independent/doc/test_pic_msg1.png" 图文消息的图片链接，支持JPG、PNG格式，较好的效果为大图 1068*455，小图150*150。
		//        }
	];

	public function getMessageType(){
		return 'news';
	}
}
