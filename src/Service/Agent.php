<?php

namespace LFPhp\WeworkSdk\Service;

use LFPhp\WeworkSdk\Base\AuthorizedService;

class Agent extends AuthorizedService {
	public static function getAgentInfo($agent_id){
		$url = '/cgi-bin/agent/get';
		$rsp = self::sendRequest($url, ['agentid' => $agent_id], false);
		$rsp->assertSuccess();

		$allow_user_ids = [];
		$tmp = $rsp->get('allow_userinfos.user');
		if($tmp){
			foreach($tmp as $item){
				$allow_user_ids[] = $item['userid'];
			}
		}
		return [
			'agent_id'             => $agent_id, //企业应用id
			'name'                 => $rsp->get('name', ''), //企业应用名称
			'square_logo_url'      => $rsp->get('square_logo_url', ''), //企业应用方形头像
			'description'          => $rsp->get('description', ''), //企业应用详情
			'allow_users'          => $allow_user_ids, //企业应用可见范围（人员），其中包括userid
			'allow_parties'        => $rsp->get('allow_partys.partyid', []), //企业应用可见范围（部门）
			'allow_tags'           => $rsp->get('allow_tags.tagid', []), //企业应用可见范围（标签）
			'close'                => $rsp->get('close'), //企业应用是否被停用
			'redirect_domain'      => $rsp->get('redirect_domain'), //企业应用可信域名
			'report_location_flag' => !!$rsp->get('report_location_flag'), //企业应用是否打开地理位置上报
			'home_url'             => !!$rsp->get('home_url'), //是否上报用户进入应用事件。
		];
	}

	/**
	 * 企业仅可获取当前凭证对应的应用；第三方仅可获取被授权的应用。
	 * @return array [[agentid, name, square_log_url], ...]
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 */
	public static function getAgentList(){
		$url = '/cgi-bin/agent/list';
		$rsp = self::sendRequest($url, null, false);
		$rsp->assertSuccess();
		return $rsp->get('agentlist');
	}
}
