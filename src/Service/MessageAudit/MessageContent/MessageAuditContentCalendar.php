<?php

namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentCalendar extends MessageAuditContentPrototype {
	public $title; //日程主题。String类型
	public $creator_name; //日程组织者。String类型
	public $attendee_name; //日程参与人。数组，内容为String类型
	public $start_time; //日程开始时间。Utc时间，单位秒
	public $end_time; //日程结束时间。Utc时间，单位秒
	public $place; //日程地点。String类型
	public $remarks; //日程备注。String类型

	public function __construct(array $data = []){
		if($data){
			$this->title = $data['calendar']['title'];
			$this->creator_name = $data['calendar']['creatorname'];
			$this->attendee_name = $data['calendar']['attendeename'];
			$this->start_time = $data['calendar']['starttime'];
			$this->end_time = $data['calendar']['endtime'];
			$this->place = $data['calendar']['place'];
			$this->remarks = $data['calendar']['remarks'];
		}
		parent::__construct($data);
	}
}
