<?php
namespace LFPhp\WeworkSdk\Service\ExternalContact;

use LFPhp\WeworkSdk\Base\AuthorizedService;

/**
 * 客户统计数据
 * Class Report
 * @package LFPhp\WeworkSdk\Service\External
 */
class Report extends AuthorizedService {
	/**
	 * 成员ID列表 与 部门ID列表不可同时为空
	 * @param int $start_time 数据起始时间(时间戳）
	 * @param int $end_time 数据结束时间(时间戳）
	 * @param array $user_id_list 成员ID列表，为空表示查询所有
	 * @param array $party_id_list 部门ID列表，为空表示查询所有
	 * @return array
	 * @see https://work.weixin.qq.com/api/doc/90001/90143/92275
	 */
	public static function getUserBehaviorData($start_time, $end_time, $user_id_list = [], $party_id_list = []){
		if(!$user_id_list && !$party_id_list){
			throw new \Exception('User id list or party id list required');
		}
		$url = '/cgi-bin/externalcontact/get_user_behavior_data';
		$param = [
			"userid"     => $user_id_list,
			"partyid"    => $party_id_list,
			"start_time" => $start_time,
			"end_time"   => $end_time,
		];
		//需要用post方法
		$rsp = self::postJson($url, $param);
		if(isset($rsp->data['errcode']) && $rsp->data['errcode'] == 40003){
			//无效的userId返回空数组
			return [];
		}
		$rsp->assertSuccess();
		return $rsp->get('behavior_data');
	}
}
