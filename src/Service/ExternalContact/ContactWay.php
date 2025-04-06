<?php

namespace LFPhp\WeworkSdk\Service\ExternalContact;

use LFPhp\WeworkSdk\Base\AuthorizedService;

/**
 * 客户联系
 * Class ContactWay
 * @package LFPhp\WeworkSdk\Service\External
 */
class ContactWay extends AuthorizedService {

	/**
	 * https://work.weixin.qq.com/api/doc/90001/90143/92576
	 * 获取配置了客户联系功能的成员列表
	 * @return array ['zhang3', 'li4']
	 */
	public static function getFollowUserList(){
		$url = '/cgi-bin/externalcontact/get_follow_user_list';
		$rsp = self::sendRequest($url, [], false);
		$rsp->assertSuccess();
		return $rsp->get('follow_user');
	}

	/**
	 * 新增【联系我】
	 * @param $data
	 * @return array 配置ID
	 * @see https://work.weixin.qq.com/api/doc/90001/90143/92577
	 */
	public static function create($data){
		$url = '/cgi-bin/externalcontact/add_contact_way';
		$rsp = self::sendRequest($url, $data);
		$rsp->assertSuccess();
		return [$rsp->get('config_id'), $rsp->get('qr_code')];
	}

	/**
	 * 编辑【联系我】
	 * @param $data
	 * @return bool
	 * @see https://work.weixin.qq.com/api/doc/90001/90143/92577
	 */
	public static function update($data){
		$url = '/cgi-bin/externalcontact/update_contact_way';
		$rsp = self::sendRequest($url, $data);
		$rsp->assertSuccess();
		return true;
	}

	/**删除「联系我」
	 * @param $config_id
	 * @return \LFPhp\WeworkSdk\Base\Response
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 */
	public static function delete($config_id){
		$url = '/cgi-bin/externalcontact/del_contact_way';
		$rsp = self::sendRequest($url, ['config_id' => $config_id]);
		return $rsp;
	}

	/**
	 * 获取【联系我】详情
	 * @param string $config_id
	 * @return array
	 */
	public static function getInfo($config_id){
		$url = '/cgi-bin/externalcontact/get_contact_way';
		$rsp = self::sendRequest($url, ['config_id' => $config_id]);
		$rsp->assertSuccess();
		return $rsp->get('contact_way');
	}

	/**
	 * 结束临时会话
	 * @param $user_id
	 * @param $external_user_id
	 * @return bool
	 */
	public static function closeTempChat($user_id, $external_user_id){
		$url = '/cgi-bin/externalcontact/close_temp_chat';
		$rsp = self::sendRequest($url, [
			'userid'          => $user_id,
			'external_userid' => $external_user_id,
		]);
		$rsp->assertSuccess();
		return true;
	}
}
