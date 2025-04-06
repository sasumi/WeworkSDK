<?php

namespace LFPhp\WeworkSdk\Service\Robot;
class RobotMessage {
	const MENTIONED_ALL = '@all';

	public static function autoMentionList($mentioned_list = []){
		$user_ids = [];
		$mobile_list = [];
		foreach($mentioned_list as $item){
			if(preg_match("/^\d{13}$/", $item)){
				$mobile_list[] = $item;
			}else{
				$user_ids[] = $item;
			}
		}
		return [$user_ids, $mobile_list];
	}

	public static function messageImage($img){
		$ctn = file_get_contents($img);
		return [
			'msgtype' => 'image',
			'image'   => [
				'base64' => base64_encode($ctn),
				'md5'    => md5($ctn),
			],
		];
	}

	public static function messageMarkdown($md){
		return [
			'msgtype'  => 'markdown',
			'markdown' => [
				'content' => $md,
			],
		];
	}

	public static function messageText($content, $mentioned_list = []){
		[$mention_user_ids, $mention_mobile_list] = self::autoMentionList($mentioned_list);
		return [
			'msgtype' => 'text',
			'text'    => [
				'content'               => $content,
				'mentioned_list'        => $mention_user_ids,
				'mentioned_mobile_list' => $mention_mobile_list,
			],
		];
	}
}
