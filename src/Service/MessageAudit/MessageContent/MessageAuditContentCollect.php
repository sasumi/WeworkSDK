<?php

namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentCollect extends MessageAuditContentPrototype {
	const TYPE_TEXT = 'Text';
	const TYPE_NUMBER = 'Number';
	const TYPE_DATE = 'Date';
	const TYPE_TIME = 'Time';

	public $room_name;  //填表消息所在的群名称。String类型
	public $creator;  //创建者在群中的名字。String类型
	public $create_time;  //创建的时间。String类型
	public $title;  //表名。String类型
	public $details = [];  //表内容。json数组类型 {"id":3,"ques":"表项3，日期","type":"Date"}
	public $id;  //表项id。Uint64类型
	public $ques;  //表项名称。String类型
	public $type;  //表项类型，有Text(文本),Number(数字),Date(日期),Time(时间)。String类型

	public function __construct(array $data = []){
		if($data){
			$this->room_name = $data['collect']['room_name'];
			$this->creator = $data['collect']['creator'];
			$this->create_time = $data['collect']['create_time'];
			$this->title = $data['collect']['title'];
			$this->details = $data['collect']['details'];
			$this->id = $data['collect']['id'];
			$this->ques = $data['collect']['ques'];
			$this->type = $data['collect']['type'];
		}
		parent::__construct($data);
	}
}
