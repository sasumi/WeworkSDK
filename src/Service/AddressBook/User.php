<?php

namespace LFPhp\WeworkSdk\Service\AddressBook;

use LFPhp\WeworkSdk\Base\AuthorizedService;

/**
 * 企业员工（成员）类
 * Class User
 * @package LFPhp\WeworkSdk\Service
 */
class User extends AuthorizedService {
	/**
	 * 根据电话查询用户ID
	 * @param $phone
	 * @return string 用户ID
	 */
	public static function getUserIdByPhone($phone){
		$url = '/cgi-bin/user/getuserid';
		$rsp = self::sendRequest($url, ['mobile' => $phone]);
		$rsp->assertSuccess();
		return $rsp->get('userid');
	}

	/**
	 * 获取部门成员
	 * @param string $department_id 获取的部门id
	 * @param int $fetch_child 是否递归获取子部门下面的成员：1-递归获取，0-只获取本部门
	 * @return array 返回格式请参考官方接口定义
	 * @see https://work.weixin.qq.com/api/doc/90001/90143/90337
	 */
	public static function getListViaDepartmentId($department_id, $fetch_child = 0){
		$url = '/cgi-bin/user/list';
		$param = [
			'department_id' => $department_id,
			'fetch_child'   => $fetch_child,
			'access_token'  => self::getAccessToken(),
		];
		$rsp = self::sendRequest($url, $param, false);
		if($rsp->isSuccess()){
			return $rsp->get('userlist');
		}
		return [];
	}

	/**
	 * 创建成员
	 * @param $data
	 * @return bool
	 * @see https://work.weixin.qq.com/api/doc/90001/90143/90331
	 */
	public static function create($data){
		$url = '/cgi-bin/user/create';
		$param = $data;
		$rsp = self::sendRequest($url, $param, false);
		$rsp->assertSuccess();
		return true;
	}

	/**
	 * 查询用户信息
	 * @param $user_id
	 * @return \LFPhp\WeworkSdk\Base\Response
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 * @see https://work.weixin.qq.com/api/doc/90001/90143/90332
	 */
	public static function getInfo($user_id){
		$url = '/cgi-bin/user/get';
		$param = ['userid' => $user_id];
		$rsp = self::sendRequest($url, $param, false);
		$rsp->assertSuccess();
		return $rsp;
	}

	/**
	 * 删除成员
	 * @param $user_id
	 * @return bool
	 */
	public static function delete($user_id){
		$url = '/cgi-bin/user/delete';
		$param = ['userid' => $user_id];
		$rsp = self::sendRequest($url, $param, false);
		$rsp->assertSuccess();
		return true;
	}

	/**
	 * userid转换
	 * @see https://open.work.weixin.qq.com/api/doc/90001/90143/95327#2.4%20userid%E7%9A%84%E8%BD%AC%E6%8D%A2
	 * @param array $user_ids
	 * @return array
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 */
	public static function userIdToOpenUserId(array $user_ids){
		$url = '/cgi-bin/batch/userid_to_openuserid';
		$param = ['userid_list' => $user_ids];
		$rsp = self::sendRequest($url, $param);
		$rsp->assertSuccess();
		return [
			'open_userid_list'    => $rsp->get('open_userid_list'),
			'invalid_userid_list' => $rsp->get('invalid_userid_list'),
		];
	}
}
