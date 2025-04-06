<?php

namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentMeeting extends MessageAuditContentPrototype {
	const STATUS_JOIN = 1;
	const STATUS_DENY = 2;
	const STATUS_PENDING = 3;
	const STATUS_UN_INVITED = 4;
	const STATUS_CANCELED = 5;
	const STATUS_EXPIRED = 6;
	const STATUS_NO_IN_ROOM = 7;

	const MEETING_TYPE_LAUNCHING = 101;
	const MEETING_TYPE_PROCESSING = 102;

	public $topic;//会议主题。String类型
	public $start_time;//会议开始时间。Utc时间
	public $end_time;//会议结束时间。Utc时间
	public $address;//会议地址。String类型
	public $remarks;//会议备注。String类型
	public $meeting_type;//会议消息类型。101发起会议邀请消息、102处理会议邀请消息。Uint32类型
	public $meeting_id;//会议id。方便将发起、处理消息进行对照。uint64类型
	public $status;//会议邀请处理状态。1 参加会议、2 拒绝会议、3 待定、4 未被邀请、5 会议已取消、6 会议已过期、7 不在房间内。Uint32类型。只有meetingtype为102的时候此字段才有内容。

	public function __construct(array $data = []){
		if($data){
			$this->topic = $data['meeting']['topic'];
			$this->start_time = $data['meeting']['starttime'];
			$this->end_time = $data['meeting']['endtime'];
			$this->address = $data['meeting']['address'];
			$this->remarks = $data['meeting']['remarks'];
			$this->meeting_type = $data['meeting']['meetingtype'];
			$this->meeting_id = $data['meeting']['meetingid'];
			$this->status = $data['meeting']['status'];
		}
		parent::__construct($data);
	}
}
