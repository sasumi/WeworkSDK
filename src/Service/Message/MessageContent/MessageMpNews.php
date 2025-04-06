<?php
namespace LFPhp\WeworkSdk\Service\Message\MessageContent;

/**
 * mpnews类型的图文消息，跟普通的图文消息一致，唯一的差异是图文内容存储在企业微信。
 * 多次发送mpnews，会被认为是不同的图文，阅读、点赞的统计会被分开计算。
 * Class MessageImageText
 * @package LFPhp\WeworkSdk\Service\Message\MessageContent
 */
class MessageMpNews extends MessagePrototype {
	public $articles = [/**         {
	 * "title": "Title", //标题，不超过128个字节，超过会自动截断（支持id转译）
	 * "thumb_media_id": "MEDIA_ID",
	 * "author": "Author", //图文消息的作者，不超过64个字节
	 * "content_source_url": "URL", //图文消息点击“阅读原文”之后的页面链接
	 * "content": "Content",  //图文消息的内容，支持html标签，不超过666 K个字节（支持id转译）
	 * "digest": "Digest description"  //图文消息的描述，不超过512个字节，超过会自动截断（支持id转译）
	 * }
	 ***/
	];

	public function getMessageType(){
		return 'mpnews';
	}
}
