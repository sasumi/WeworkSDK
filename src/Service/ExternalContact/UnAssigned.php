<?php
namespace LFPhp\WeworkSdk\Service\ExternalContact;

use LFPhp\WeworkSdk\Base\AuthorizedService;

/**
 * 获取离职成员相关客户操作
 * Class UnAssigned
 * @package LFPhp\WeworkSdk\Service\External
 */
class UnAssigned extends AuthorizedService {
	/**
	 * 获取离职成员的客户列表
	 * @param int $page_index 分页查询，要查询页号，从0开始
	 * @param int $page_size 每次返回的最大记录数，默认为1000，最大值为1000
	 * @return array [$list, $is_last] 返回数据按离职时间的降序排列
	 * @see https://work.weixin.qq.com/api/doc/90001/90143/92273
	 */
	public static function getList($page_index = 0, $page_size = 1000){
		$url = '/cgi-bin/externalcontact/get_unassigned_list';
		$rsp = self::sendRequest($url, [
			'page_id'   => $page_index,
			'page_size' => $page_size,
		]);
		$rsp->assertSuccess();
		$list = $rsp->get('info');
		$is_last = $rsp->get('is_last');
		return [$list, $is_last];
	}

	/**
	 * 离职成员的外部联系人再分配
	 * @param string $external_user_id 外部联系人的userid，注意不是企业成员的帐号
	 * @param string $handover_user_id 离职成员的userid
	 * @param string $takeover_user_id 接替成员的userid
	 * @return bool
	 */
	public static function transfer($external_user_id, $handover_user_id, $takeover_user_id){
		$url = '/cgi-bin/externalcontact/transfer';
		$rsp = self::sendRequest($url, [
			'external_userid' => $external_user_id,
			'handover_userid' => $handover_user_id,
			'takeover_userid' => $takeover_user_id,
		]);
		$rsp->assertSuccess();
		return true;
	}
}
