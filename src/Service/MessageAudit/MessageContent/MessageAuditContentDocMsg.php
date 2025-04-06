<?php

namespace LFPhp\WeworkSdk\Service\MessageAudit\MessageContent;

class MessageAuditContentDocMsg extends MessageAuditContentPrototype {
	public $title; //在线文档名称
	public $link_url; //在线文档链接
	public $doc_creator; //在线文档创建者。本企业成员创建为userid；外部企业成员创建为external_userid

	public function __construct(array $data = []){
		if($data){
			$this->title = $data['docmsg']['title'];
			$this->link_url = $data['docmsg']['link_url'];
			$this->doc_creator = $data['docmsg']['doc_creator'];
		}
		parent::__construct($data);
	}
}
