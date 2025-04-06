<?php

namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentVote extends MessageAuditContentPrototype {
	const TYPE_CREATE = 101;
	const TYPE_JOIN = 102;

	public $vote_title;//投票主题。String类型
	public $vote_item;//投票选项，可能多个内容。String数组
	public $vote_type;//投票类型.101发起投票、102参与投票。Uint32类型
	public $vote_id;//投票id，方便将参与投票消息与发起投票消息进行前后对照。String类型

	public function __construct(array $data = []){
		if($data){
			$this->vote_title = $data['vote']['votetitle'];
			$this->vote_item = $data['vote']['voteitem'];
			$this->vote_type = $data['vote']['votetype'];
			$this->vote_id = $data['vote']['voteid'];
		}
		parent::__construct($data);
	}
}
