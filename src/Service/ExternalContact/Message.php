<?php
namespace LFPhp\WeworkSdk\Service\ExternalContact;

use LFPhp\WeworkSdk\Base\AuthorizedService;
use LFPhp\WeworkSdk\Exception\ConnectException;

class Message extends AuthorizedService {
	const CHAT_TYPE_SINGLE = 'single';
	const CHAT_TYPE_GROUP = 'group';

	/**
	 * 企业可通过此接口添加企业群发消息的任务并通知客服人员发送给相关客户或客户群。
	 * <br/>
	 * 注意：调用该接口并不会直接发送消息给客户/客户群，需要相关的客服人员操作以后才会实际发送（客服人员的企业微信需要升级到2.7.5及以上版本）
	 * <br/>
	 * 同一个企业每个自然月内仅可针对一个客户/客户群<b><i>发送4条消息</i></b>，超过限制的用户将会被忽略。
	 * @see https://work.weixin.qq.com/api/doc/90001/90143/92698
	 * @param array $content
	 * "text": {
	 * "content":"文本消息内容"
	 * },
	 * "image": {
	 * "media_id": "MEDIA_ID",
	 * "pic_url":"http://p.qpic.cn/pic_wework/3474110808/7a6344sdadfwehe42060/0"
	 * },
	 * "link": {
	 * "title": "消息标题",
	 * "picurl": "https://example.pic.com/path",
	 * "desc": "消息描述",
	 * "url": "https://example.link.com/path"
	 * },
	 * "miniprogram": {
	 * "title": "消息标题",
	 * "pic_media_id": "MEDIA_ID",
	 * "appid": "wx8bd80126147dfAAA",
	 * "page": "/path/index.html"
	 * }
	 * @param array $receiver_list 外部客户ID数组
	 * @param $sender
	 * @param string $chat_type single、group
	 * @return array [msgid, fail_id_list]
	 * @throws ConnectException
	 */
	public static function sendMessage($content, array $receiver_list, $sender, $chat_type){
		$uri = "/cgi-bin/externalcontact/add_msg_template";
		$param = [
			"chat_type"       => $chat_type,
			"sender"          => $sender,
			"external_userid" => $receiver_list,
		];
		$param = array_merge($param, $content);
		$rsp = self::sendRequest($uri, $param);
		if($rsp->code == 41048){
			return ['', $rsp->get('fail_list')]; //全部员工均不可发送
		}
		$rsp->assertSuccess();
		return [$rsp->get('msgid'), $rsp->get('fail_list')];
	}

	/**
	 * 获取群消息发送结果
	 * @param $wxMsgId
	 * @return array|mixed|null
	 * @throws ConnectException
	 */
	public static function getMsgRecode($wxMsgId){
		$uri = "/cgi-bin/externalcontact/get_group_msg_result";
		$param = [
			"msgid" => $wxMsgId,
		];
		$rsp = self::sendRequest($uri, $param);
		return $rsp->get('detail_list');
	}

	/**
	 * 获取企业全部群发记录
	 * @param string $msgId
	 * @param array $result
	 * @param string $cursor
	 * @return array
	 * @throws ConnectException
	 */
	public static function getGroupMsgTask(string $msgId, &$result = [], $cursor = ''){
		$uri = "/cgi-bin/externalcontact/get_groupmsg_task";
		$param = [
			"msgid"  => $msgId,
			'cursor' => $cursor,
			'limit'  => 1000,
		];
		$rsp = self::sendRequest($uri, $param);
		$rsp->assertSuccess();
		$cursor = $rsp->next_cursor;
		$taskList = $rsp->task_list;
		if(!$taskList)
			return $result;
		$result = $result ? array_merge($result, $taskList) : $taskList;
		if($cursor){
			self::getGroupMsgTask($msgId, $result, $cursor);
		}
		return $result;
	}

	/**
	 * 获取企业群发成员执行结果
	 * @param string $msgId 群发消息id
	 * @param string $userId 发送成员user_id
	 * @param array $result 结果集
	 * @param string $cursor 偏移量
	 * @return array
	 * @throws ConnectException
	 */
	public static function getGroupMsgSendResult(string $msgId, string $userId, &$result = [], $cursor = ''){
		$uri = "/cgi-bin/externalcontact/get_group_msg_result";
		$param = [
			"msgid"  => $msgId,
			'userid' => $userId,
			'limit'  => 10000,
			'cursor' => $cursor,
		];
		$rsp = self::sendRequest($uri, $param);
		if($rsp->code != 0)
			return [];
		$cursor = $rsp->next_cursor;
		$detailList = $rsp->detail_list;
		$result = $result ? array_merge($result, $detailList) : $detailList;
		if($cursor){
			return self::getGroupMsgSendResult($msgId, $userId, $result, $cursor);
		}
		return $result;
	}
}
