<?php
namespace LFPhp\WeworkSdk\Service\ExternalContact;

use LFPhp\WeworkSdk\Base\AuthorizedService;

class Transfer extends AuthorizedService {
	/**
	 * 分配在职成员的客户
	 * @see https://open.work.weixin.qq.com/api/doc/90000/90135/92125
	 * @param string $handover_userid
	 * @param string $takeover_userid
	 * @param array $external_userid
	 * @param string $transfer_success_msg
	 * @return array|mixed
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 * 权限说明：
	 * 企业需要使用“客户联系”secret或配置到“可调用应用”列表中的自建应用secret所获取的accesstoken来调用（accesstoken如何获取？）。
	 * 第三方应用需拥有“企业客户权限->客户联系->在职继承”权限
	 * 接替成员必须在此第三方应用或自建应用的可见范围内。
	 * 接替成员需要配置了客户联系功能。
	 * 接替成员需要在企业微信激活且已经过实名认证。
	 * 在职成员的每位客户最多被分配2次。客户被转接成功后，将有90个自然日的服务关系保护期，保护期内的客户无法再次被分配。
	 *                                                   *      {
	 * "errcode": 0,
	 * "errmsg": "ok",
	 * "customer":
	 * [
	 * {
	 * "external_userid":"woAJ2GCAAAXtWyujaWJHDDGi0mACAAAA",
	 * "errcode":40096
	 * },
	 * {
	 * "external_userid":"woAJ2GCAAAXtWyujaWJHDDGi0mACBBBB",
	 * "errcode":0
	 * }
	 * ]
	 * }
	 */
	public static function transferCustomer(string $handover_userid, string $takeover_userid, array $external_userid, $transfer_success_msg = ''){
		$url = '/cgi-bin/externalcontact/transfer_customer';
		$params = [
			'handover_userid' => $handover_userid,
			'takeover_userid' => $takeover_userid,
			'external_userid' => $external_userid,
		];
		if($transfer_success_msg){
			$params['transfer_success_msg'] = $transfer_success_msg;
		}
		$rsp = self::sendRequest($url, $params);
		$rsp->assertSuccess();
		return $rsp->get('customer');
	}

	/**
	 * 离职继承-分配离职成员的客户
	 * @see https://open.work.weixin.qq.com/api/doc/90000/90135/94081
	 * @param string $handover_userid
	 * @param string $takeover_userid
	 * @param array $external_userid
	 * @return array|mixed
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 */
	public static function transferResignedCustomer(string $handover_userid, string $takeover_userid, array $external_userid){
		$url = '/cgi-bin/externalcontact/resigned/transfer_customer';
		$params = [
			'handover_userid' => $handover_userid,
			'takeover_userid' => $takeover_userid,
			'external_userid' => $external_userid,
		];
		$rsp = self::sendRequest($url, $params);
		$rsp->assertSuccess();
		return $rsp->get('customer');
	}

	/**
	 * 在职继承--查询客户接替状态
	 * @see https://open.work.weixin.qq.com/api/doc/90000/90135/94088
	 * @param string $handover_userid 原添加成员的userid
	 * @param string $takeover_userid 接替成员的userid
	 * @param $cursor
	 * @return array
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 */
	public static function transferResult(string $handover_userid, string $takeover_userid, $cursor): array{
		$url = '/cgi-bin/externalcontact/transfer_result';
		$params = [
			'handover_userid' => $handover_userid,
			'takeover_userid' => $takeover_userid,
			'cursor'          => $cursor,
		];
		$rsp = self::sendRequest($url, $params);
		$rsp->assertSuccess();
		$customer = $rsp->get('customer');
		$next_cursor = $rsp->get('next_cursor');
		return [$customer, $next_cursor];
	}

	/**
	 * 离职继承-查询客户接替状态
	 * @see https://open.work.weixin.qq.com/api/doc/90000/90135/94082
	 * @param string $handover_userid 原添加成员的userid
	 * @param string $takeover_userid 接替成员的userid
	 * @param $cursor
	 * @return array
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 */
	public static function transferResignedResult(string $handover_userid, string $takeover_userid, $cursor){
		$url = '/cgi-bin/externalcontact/resigned/transfer_result';
		$params = [
			'handover_userid' => $handover_userid,
			'takeover_userid' => $takeover_userid,
			'cursor'          => $cursor,
		];
		$rsp = self::sendRequest($url, $params);
		$rsp->assertSuccess();
		$customer = $rsp->get('customer');
		$next_cursor = $rsp->get('next_cursor');
		return [$customer, $next_cursor];
	}

	/**
	 * 离职继承--获取待分配的离职成员列表
	 * @see https://open.work.weixin.qq.com/api/doc/90000/90135/92124
	 * @param int $page_id 分页查询，要查询页号，从0开始
	 * @param int $page_size 每次返回的最大记录数，默认为1000，最大值为1000
	 * @param string $cursor
	 * @return array
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 * @becarful 当page_id为1，page_size为100时，表示取第101到第200条记录。由于每个成员的客户数不超过5万，故page_id*page_size 必须小于5万。
	 */
	public static function getUnassignedList(int $page_id, int $page_size, string $cursor = ''): array{
		$url = '/cgi-bin/externalcontact/get_unassigned_list';
		$params = [
			'page_id'   => $page_id,
			'page_size' => $page_size,
			'cursor'    => $cursor,
		];
		$rsp = self::sendRequest($url, $params);
		$rsp->assertSuccess();
		$info = $rsp->get('info');
		$is_last = $rsp->get('is_last');
		$next_cursor = $rsp->get('next_cursor');

		return [$info, $is_last, $next_cursor];
	}

	/**
	 * 离职继承--分配离职成员的客户群
	 * @see https://open.work.weixin.qq.com/api/doc/90000/90135/92127
	 * @param array $chat_id_list
	 * @param string $new_owner
	 * @return array
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 */
	public static function transferGroupchat(array $chat_id_list, string $new_owner): array{
		$url = '/cgi-bin/externalcontact/groupchat/transfer';
		$params = [
			'chat_id_list' => $chat_id_list,
			'new_owner'    => $new_owner,
		];
		$rsp = self::sendRequest($url, $params);
		$rsp->assertSuccess();
		return $rsp->get('failed_chat_list');
	}

}
