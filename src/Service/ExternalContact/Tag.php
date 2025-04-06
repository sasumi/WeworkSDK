<?php

namespace LFPhp\WeworkSdk\Service\ExternalContact;

use LFPhp\Logger\Logger;
use LFPhp\WeworkSdk\Base\AuthorizedService;

class Tag extends AuthorizedService {
	/**
	 * 获取标签列表
	 * 同时传递tag_id和group_id时，忽略tag_id，仅以group_id作为过滤条件
	 * @param array $group_ids 格式如：[etXXXXXXXXXX, etXXXXXXXXYY, ...]
	 * @param array $tag_ids 格式如：[etXXXXXXXXXX, etXXXXXXXXYY, ...]
	 * @return array
	 * ["group_id": "TAG_GROUP_ID1",
	 * "group_name": "GROUP_NAME",
	 * "create_time": 1557838797,
	 * "order": 1,
	 * "deleted": false,
	 * "tag": [{
	 * "id": "TAG_ID1",
	 * "name": "NAME1",
	 * "create_time": 1557838797,
	 * "order": 1,
	 * "deleted": false
	 * },
	 * {
	 * "id": "TAG_ID2",
	 * "name": "NAME2",
	 * "create_time": 1557838797,
	 * "order": 2,
	 * "deleted": true
	 * }
	 * ]
	 */
	public static function getList(array $group_ids = [], array $tag_ids = []){
		$url = '/cgi-bin/externalcontact/get_corp_tag_list';
		$param = [];
		if($group_ids){
			$param['group_id'] = $group_ids;
		}
		if($tag_ids){
			$param['tag_id'] = $tag_ids;
		}
		$rsp = self::postJson($url, $param);
		$rsp->assertSuccess();
		return $rsp->get('tag_group');
	}

	/**
	 * 添加企业客户标签
	 * @param array $data
	 * @return \LFPhp\WeworkSdk\Base\Response
	 * @see https://work.weixin.qq.com/api/doc/90001/90143/92696
	 */
	public static function create(array $data){
		$url = '/cgi-bin/externalcontact/add_corp_tag';
		$rsp = self::postJson($url, $data);
		$rsp->assertSuccess();
		return $rsp->get('tag_group');
	}

	/**
	 * 编辑企业客户标签
	 * @param array $data
	 * @return bool
	 */
	public static function update($tag_id, $tag_name = '', $order = ''){
		$url = '/cgi-bin/externalcontact/edit_corp_tag';
		$param = [
			'id' => $tag_id,
		];
		if($tag_name)
			$param['name'] = $tag_name;
		if($order)
			$param['order'] = $order;

		$rsp = self::postJson($url, $param);
		$rsp->assertSuccess();
		return true;
	}

	/**
	 * 删除企业客户标签
	 * @param array $tag_id_list
	 * @param array $group_id_list
	 * @return bool
	 */
	public static function delete(array $tag_id_list = [], array $group_id_list = []){
		$url = '/cgi-bin/externalcontact/del_corp_tag';
		$param = [
			'tag_id'   => $tag_id_list,
			'group_id' => $group_id_list,
		];
		$rsp = self::postJson($url, $param);
		$rsp->assertSuccess();
		return true;
	}

	/**
	 * 编辑客户企业标签
	 * @param string $user_id
	 * @param string $external_user_id
	 * @param array $add_tags ["TAGID1","TAGID2"]
	 * @param array $remove_tags ["TAGID3","TAGID4"]
	 * @return bool
	 */
	public static function markContact($user_id, $external_user_id, $add_tags = [], $remove_tags = []){
		$url = '/cgi-bin/externalcontact/mark_tag';
		$param = [
			'userid'          => $user_id,
			'external_userid' => $external_user_id,
			'add_tag'         => $add_tags,
			'remove_tag'      => $remove_tags,
		];
		$logger = logger::instance(__CLASS__);
		$logger->info("tag_data_request", $url, json_encode($param));
		$rsp = self::postJson($url, $param);
		$rsp->assertSuccess();
		return true;
	}
}
