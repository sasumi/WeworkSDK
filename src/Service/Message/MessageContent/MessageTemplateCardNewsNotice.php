<?php
namespace LFPhp\WeworkSdk\Service\Message\MessageContent;

/**
 * @see https://work.weixin.qq.com/api/doc/90001/90143/90372#%E6%A8%A1%E6%9D%BF%E5%8D%A1%E7%89%87%E6%B6%88%E6%81%AF
 * 模板卡片消息--图文展示型
 * @package LFPhp\WeworkSdk\Service\Message\MessageContent
 */
class MessageTemplateCardNewsNotice extends MessagePrototype {

	const CARD_TYPE_NEWS_NOTICE = 'news_notice';

	public $articles = [/**
	 * "template_card" : {
	 * "card_type" : "news_notice",
	 * "source" : {
	 * "icon_url": "图片的url",
	 * "desc": "企业微信",
	 * "desc_color": 1
	 * },
	 * "action_menu": {
	 * "desc": "卡片副交互辅助文本说明",
	 * "action_list": [
	 * {"text": "接受推送", "key": "A"},
	 * {"text": "不再推送", "key": "B"}
	 * ]
	 * },
	 * "task_id": "task_id",
	 * "main_title" : {
	 * "title" : "欢迎使用企业微信",
	 * "desc" : "您的好友正在邀请您加入企业微信"
	 * },
	 * "quote_area": {
	 * "type": 1,
	 * "url": "https://work.weixin.qq.com",
	 * "title": "企业微信的引用样式",
	 * "quote_text": "企业微信真好用呀真好用"
	 * },
	 * "image_text_area": {
	 * "type": 1,
	 * "url": "https://work.weixin.qq.com",
	 * "title": "企业微信的左图右文样式",
	 * "desc": "企业微信真好用呀真好用",
	 * "image_url": "https://img.iplaysoft.com/wp-content/uploads/2019/free-images/free_stock_photo_2x.jpg"
	 * },
	 * "card_image": {
	 * "url": "图片的url",
	 * "aspect_ratio": 1.3
	 * },
	 * "vertical_content_list": [
	 * {
	 * "title": "惊喜红包等你来拿",
	 * "desc": "下载企业微信还能抢红包！"
	 * }
	 * ],
	 * "horizontal_content_list" : [
	 * {
	 * "keyname": "邀请人",
	 * "value": "张三"
	 * },
	 * {
	 * "type": 1,
	 * "keyname": "企业微信官网",
	 * "value": "点击访问",
	 * "url": "https://work.weixin.qq.com"
	 * },
	 * {
	 * "type": 2,
	 * "keyname": "企业微信下载",
	 * "value": "企业微信.apk",
	 * "media_id": "文件的media_id"
	 * },
	 * {
	 * "type": 3,
	 * "keyname": "员工信息",
	 * "value": "点击查看",
	 * "userid": "zhangsan"
	 * }
	 * ],
	 * "jump_list" : [
	 * {
	 * "type": 1,
	 * "title": "企业微信官网",
	 * "url": "https://work.weixin.qq.com"
	 * },
	 * {
	 * "type": 2,
	 * "title": "跳转小程序",
	 * "appid": "小程序的appid",
	 * "pagepath": "/index.html"
	 * }
	 * ],
	 * "card_action": {
	 * "type": 2,
	 * "url": "https://work.weixin.qq.com",
	 * "appid": "小程序的appid",
	 * "pagepath": "/index.html"
	 * }
	 * },
	 **/
	];

	public function getMessageType(){
		return 'template_card';
	}
}
