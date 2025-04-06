<?php

namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

use Exception;
use LFPhp\WeworkSdk\Base\DataPrototype;

/**
 * Class MessageAuditContentPrototype
 * @package LFPhp\WeworkSdk\Service\MessageAudit\MessageContent
 * @see https://open.work.weixin.qq.com/api/doc/90000/90135/91774
 */
abstract class MessageAuditContentPrototype extends DataPrototype {
	const ACTION_SEND = 'send';
	const ACTION_RECALL = 'recall';
	const ACTION_SWITCH = 'switch';

	const MSG_TYPE_TEXT = 'text';
	const MSG_TYPE_IMAGE = 'image';
	const MSG_TYPE_REVOKE = 'revoke';
	const MSG_TYPE_AGREE = 'agree';
	const MSG_TYPE_DISAGREE = 'disagree';
	const MSG_TYPE_VOICE = 'voice';
	const MSG_TYPE_VIDEO = 'video';
	const MSG_TYPE_CARD = 'card';
	const MSG_TYPE_LOCATION = 'location';
	const MSG_TYPE_EMOTION = 'emotion';
	const MSG_TYPE_FILE = 'file';
	const MSG_TYPE_LINK = 'link';
	const MSG_TYPE_WEAPP = 'weapp';
	const MSG_TYPE_CHATRECORD = 'chatrecord';
	const MSG_TYPE_TODO = 'todo';
	const MSG_TYPE_VOTE = 'vote';
	const MSG_TYPE_COLLECT = 'collect';
	const MSG_TYPE_REDPACKET = 'redpacket';
	const MSG_TYPE_MEETING = 'meeting';
	const MSG_TYPE_DOCMSG = 'docmsg';
	const MSG_TYPE_MARKDOWN = 'markdown';
	const MSG_TYPE_NEWS = 'news';
	const MSG_TYPE_CALENDAR = 'calendar';
	const MSG_TYPE_MIXED = 'mixed';
	const MSG_TYPE_MEETING_VOICE_CALL = 'meeting_voice_call';
	const MSG_TYPE_VOIP_DOC_SHARE = 'voip_doc_share';
	const MSG_TYPE_EXTERNAL_REDPACKET = 'external_redpacket';

	const MSG_TYPE_CLASS_MAP = [
		self::MSG_TYPE_TEXT               => MessageAuditContentText::class,
		self::MSG_TYPE_IMAGE              => MessageAuditContentImage::class,
		self::MSG_TYPE_REVOKE             => MessageAuditContentRevoke::class,
		self::MSG_TYPE_AGREE              => MessageAuditContentAgree::class,
		self::MSG_TYPE_DISAGREE           => MessageAuditContentDisagree::class,
		self::MSG_TYPE_VOICE              => MessageAuditContentVoice::class,
		self::MSG_TYPE_VIDEO              => MessageAuditContentVideo::class,
		self::MSG_TYPE_CARD               => MessageAuditContentCard::class,
		self::MSG_TYPE_LOCATION           => MessageAuditContentLocation::class,
		self::MSG_TYPE_EMOTION            => MessageAuditContentEmotion::class,
		self::MSG_TYPE_FILE               => MessageAuditContentFile::class,
		self::MSG_TYPE_LINK               => MessageAuditContentLink::class,
		self::MSG_TYPE_WEAPP              => MessageAuditContentWeapp::class,
		self::MSG_TYPE_CHATRECORD         => MessageAuditContentChatRecord::class,
		self::MSG_TYPE_TODO               => MessageAuditContentTodo::class,
		self::MSG_TYPE_VOTE               => MessageAuditContentVote::class,
		self::MSG_TYPE_COLLECT            => MessageAuditContentCollect::class,
		self::MSG_TYPE_REDPACKET          => MessageAuditContentRedpacket::class,
		self::MSG_TYPE_MEETING            => MessageAuditContentMeeting::class,
		self::MSG_TYPE_DOCMSG             => MessageAuditContentDocMsg::class,
		self::MSG_TYPE_MARKDOWN           => MessageAuditContentMarkdown::class,
		self::MSG_TYPE_NEWS               => MessageAuditContentNews::class,
		self::MSG_TYPE_CALENDAR           => MessageAuditContentCalendar::class,
		self::MSG_TYPE_MIXED              => MessageAuditContentMixed::class,
		self::MSG_TYPE_MEETING_VOICE_CALL => MessageAuditContentMeetingVoiceCall::class,
		self::MSG_TYPE_VOIP_DOC_SHARE     => MessageAuditContentVoipDocShare::class,
		self::MSG_TYPE_EXTERNAL_REDPACKET => MessageAuditContentExternalRedpacket::class,
	];

	const MSG_TYPE_TEXT_MAP = [
		self::MSG_TYPE_TEXT               => '文本',
		self::MSG_TYPE_IMAGE              => '图片',
		self::MSG_TYPE_REVOKE             => '撤回',
		self::MSG_TYPE_AGREE              => '同意会话聊天内容',
		self::MSG_TYPE_DISAGREE           => '不同意会话聊天内容',
		self::MSG_TYPE_VOICE              => '语音',
		self::MSG_TYPE_VIDEO              => '视频',
		self::MSG_TYPE_CARD               => '名片',
		self::MSG_TYPE_LOCATION           => '位置',
		self::MSG_TYPE_EMOTION            => '表情',
		self::MSG_TYPE_FILE               => '文件',
		self::MSG_TYPE_LINK               => '链接',
		self::MSG_TYPE_WEAPP              => '小程序',
		self::MSG_TYPE_CHATRECORD         => '会话记录',
		self::MSG_TYPE_TODO               => '待办',
		self::MSG_TYPE_VOTE               => '投票',
		self::MSG_TYPE_COLLECT            => '填表',
		self::MSG_TYPE_REDPACKET          => '红包',
		self::MSG_TYPE_MEETING            => '会议邀请',
		self::MSG_TYPE_DOCMSG             => '在线文档',
		self::MSG_TYPE_MARKDOWN           => 'MarkDown格式',
		self::MSG_TYPE_NEWS               => '图文',
		self::MSG_TYPE_CALENDAR           => '日程',
		self::MSG_TYPE_MIXED              => '混合',
		self::MSG_TYPE_MEETING_VOICE_CALL => '音频存档',
		self::MSG_TYPE_VOIP_DOC_SHARE     => '音频共享文档',
		self::MSG_TYPE_EXTERNAL_REDPACKET => '互通红包',
	];

	public $msgid; //消息id，消息的唯一标识，企业可以使用此字段进行消息去重。String类型
	public $action; //消息动作，目前有send(发送消息)/recall(撤回消息)/switch(切换企业日志)三种类型。String类型
	public $from; //消息发送方id。同一企业内容为userid，非相同企业为external_userid。消息如果是机器人发出，也为external_userid。String类型
	public $to_list = []; //消息接收方列表，可能是多个，同一个企业内容为userid，非相同企业为external_userid。数组，内容为string类型
	public $roomid; //群聊消息的群id。如果是单聊则为空。String类型
	public $msg_time; //消息发送时间戳，utc时间，ms单位。
	public $msgtype; //消息类型,String类型

	/**
	 * 解析消息类型
	 * @param array $data
	 * @return MessageAuditContentText|MessageAuditContentImage|MessageAuditContentRevoke|MessageAuditContentAgree|MessageAuditContentVoice|MessageAuditContentVideo|MessageAuditContentCard|MessageAuditContentLocation|MessageAuditContentEmotion|MessageAuditContentFile|MessageAuditContentLink|MessageAuditContentWeapp|MessageAuditContentChatRecord|MessageAuditContentTodo|MessageAuditContentVote|MessageAuditContentCollect|MessageAuditContentRedpacket|MessageAuditContentMeeting|MessageAuditContentDocMsg|MessageAuditContentMarkdown|MessageAuditContentNews|MessageAuditContentCalendar|MessageAuditContentMixed|MessageAuditContentMeetingVoiceCall|MessageAuditContentVoipDocShare|MessageAuditContentExternalRedpacket
	 * @throws \\Exception
	 */
	public static function resolveMessageAuditContent(array $data){
		$type = $data['msgtype'];
		if(!isset(self::MSG_TYPE_CLASS_MAP[$type])){
			throw new Exception("message type no found: $type");
		}
		$class = self::MSG_TYPE_CLASS_MAP[$type];
		if(!class_exists($class)){
			throw new Exception("class relative to msgtype($type $class) no found");
		}
		return new $class($data);
	}

	/**
	 * MessageAuditContentPrototype constructor.
	 * @param array $data
	 */
	public function __construct(array $data = []){
		if($data && $data['msgtype']){
			$type = $data['msgtype'];
			unset($data[$type]);
		}
		parent::__construct($data);
	}
}
