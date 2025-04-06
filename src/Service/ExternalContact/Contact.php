<?php

namespace LFPhp\WeworkSdk\Service\ExternalContact;

use LFPhp\WeworkSdk\Base\AuthorizedService;

/**
 * 外部客户
 * Class Contact
 * @package LFPhp\WeworkSdk\Service\External
 */
class Contact extends AuthorizedService {
	/**
	 * 获取客户列表
	 * @param string $user_id 企业成员
	 * @return array 客户ID列表 [ "woAJ2GCAAAXtWyujaWJHDDGi0mACAAA", "wmqfasd1e1927831291723123109rAAA"]
	 */
	public static function getList($user_id){
		$url = '/cgi-bin/externalcontact/list';
		$rsp = self::sendRequest($url, ['userid' => $user_id], false);
		if(!$rsp->isSuccess()){
			return [];
		}
		return $rsp->get('external_userid');
	}

	/**
	 * 获取当前企业所有外部客户列表
	 * @see https://open.work.weixin.qq.com/wwopen/devtool/interface?doc_id=15445
	 * @return array
	 */
	public static function getAllList(){
		$follow_list = ContactWay::getFollowUserList();
		$contact_list = [];
		foreach($follow_list as $user_id){
			$external_user_id_list = self::getList($user_id);
			foreach($external_user_id_list as $external_user_id){
				$external_user_info = self::getInfo($external_user_id);
				$contact_list[] = $external_user_info;
			}
		}
		return $contact_list;
	}

	/**
	 * 获取当前企业所有外部客户ID列表
	 * @see https://open.work.weixin.qq.com/wwopen/devtool/interface?doc_id=15445
	 * @return array
	 */
	public static function getAllIDList(){
		$follow_list = ContactWay::getFollowUserList();
		$contact_id_list = [];
		foreach($follow_list as $user_id){
			$external_user_id_list = self::getList($user_id);
			$contact_id_list = array_merge($contact_id_list, $external_user_id_list);
		}
		return $contact_id_list;
	}

	/**
	 * 获取客户详情
	 * @param $external_user_id
	 * @return array
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 * @see https://work.weixin.qq.com/api/doc/90001/90143/92265
	 */
	public static function getInfo($external_user_id){
		$url = '/cgi-bin/externalcontact/get';
		$rsp = self::sendRequest($url, ['external_userid' => $external_user_id], false);
		if(!$rsp->isSuccess()){
			return [];
		}
		$external_user_id = $rsp->get('external_contact');
		$follow_user = $rsp->get('follow_user');

		return ['external_contact' => $external_user_id, 'follow_user' => $follow_user];
	}

	/**
	 * 修改客户备注信息
	 * @param $user_id
	 * @param $external_user_id
	 * @param array $remark_info
	 * [ "remark":"备注信息",
	 * "description":"描述信息",
	 * "remark_company":"腾讯科技",
	 * "remark_mobiles":[
	 * "13800000001",
	 * "13800000002"
	 * ],
	 * "remark_pic_mediaid":"MEDIAID"
	 * ]
	 * @return bool
	 */
	public static function updateRemark($user_id, $external_user_id, array $remark_info){
		$url = '/cgi-bin/externalcontact/remark';
		$param = array_merge([
			'userid'          => $user_id,
			'external_userid' => $external_user_id,
		], $remark_info);
		$rsp = self::sendRequest($url, $param);
		$rsp->assertSuccess();
		return true;
	}

	/**
	 * 批量获取联系人
	 * https://open.work.weixin.qq.com/api/doc/90001/90143/93010
	 * @param string[] $user_ids
	 * @param string $cursor
	 * @param int $limit
	 * @return array
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 */
	public static function batchGetUser(array $user_ids, $cursor = '', $limit = 100){
		$url = '/cgi-bin/externalcontact/batch/get_by_user';
		$param = [
			'userid_list' => $user_ids,
			'cursor'      => $cursor,
			'limit'       => $limit,
		];
		$retries = 3;
		$rsp = self::sendRequest($url, $param, 'true', [], $retries);
		if(!$rsp->isSuccess()){
			return [[], ''];
		}
		return [$rsp->external_contact_list, $rsp->next_cursor];
	}

	/**
	 * 转换external_userid
	 * @see https://open.work.weixin.qq.com/api/doc/90001/90143/95327#%E8%BD%AC%E6%8D%A2external_userid
	 * @param array $external_userids
	 * @return array|mixed|null
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 */
	public static function getNewExternalUserId(array $external_userids){
		$url = '/cgi-bin/externalcontact/get_new_external_userid';
		$param = [
			'external_userid_list' => $external_userids,
		];
		$rsp = self::sendRequest($url, $param);
		$rsp->assertSuccess();
		return $rsp->get('items');
	}

	/**
	 * 代开发应用external_user转换为第三方external_userid
	 * @see https://open.work.weixin.qq.com/api/doc/90001/90143/95195
	 * @param $external_userid
	 * @return array|mixed
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 */
	public static function toServiceExternalUserId($external_userid){
		$url = '/cgi-bin/externalcontact/to_service_external_userid';
		$param = [
			'external_userid' => $external_userid,
		];
		$rsp = self::sendRequest($url, $param);
		$rsp->assertSuccess();
		return $rsp->get('external_userid');
	}
}
