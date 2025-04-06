<?php

namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentMeetingVoiceCall extends MessageAuditContentPrototype {
	public $action;//消息动作，目前有send(发送消息)/recall(撤回消息)/switch(切换企业日志)三种类型。String类型
	public $from;//消息发送方id。同一企业内容为userid，非相同企业为external_userid。消息如果是机器人发出，也为external_userid。String类型
	public $to_list;//消息接收方列表，可能是多个，同一个企业内容为userid，非相同企业为external_userid。数组，内容为string类型
	public $msg_time;//消息发送时间戳，utc时间,单位毫秒。
	public $voice_id;//String类型, 音频id
	public $meeting_voice_call;//音频消息内容。包括结束时间、fileid，可能包括多个demofiledata、sharescreendata消息，demofiledata表示文档共享信息，sharescreendata表示屏幕共享信息。Object类型
	public $end_time;//音频结束时间。uint32类型 | 文档共享结束时间。Uint32类型 | 屏幕共享结束时间。Uint32类型
	public $sdk_file_id;//sdkfileid。音频媒体下载的id。String类型
	public $demo_file_data;//文档分享对象，Object类型
	public $filename;//文档共享名称。String类型
	public $demo_operator;//文档共享操作用户的id。String类型
	public $start_time;//文档共享开始时间。Uint32类型 | 屏幕共享开始时间。Uint32类型
	public $share_screen_data;//屏幕共享对象，Object类型
	public $share;//屏幕共享用户的id。String类型

	public function __construct(array $data = []){
		if($data){
			$this->action = $data['meeting_voice_call']['action'];
			$this->from = $data['meeting_voice_call']['from'];
			$this->to_list = $data['meeting_voice_call']['tolist'];
			$this->msg_time = $data['meeting_voice_call']['msgtime'];
			$this->voice_id = $data['meeting_voice_call']['voiceid'];
			$this->meeting_voice_call = $data['meeting_voice_call']['meeting_voice_call'];
			$this->end_time = $data['meeting_voice_call']['endtime'];
			$this->sdk_file_id = $data['meeting_voice_call']['sdkfileid'];
			$this->demo_file_data = $data['meeting_voice_call']['demofiledata'];
			$this->filename = $data['meeting_voice_call']['filename'];
			$this->demo_operator = $data['meeting_voice_call']['demooperator'];
			$this->start_time = $data['meeting_voice_call']['starttime'];
			$this->share_screen_data = $data['meeting_voice_call']['sharescreendata'];
			$this->share = $data['meeting_voice_call']['share'];
		}
		parent::__construct($data);
	}
}
