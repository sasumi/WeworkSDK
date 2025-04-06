<?php
namespace LFPhp\WeworkSdk\Service\ExternalContact;

use LFPhp\WeworkSdk\Base\AuthorizedService;

class Group extends AuthorizedService {
	const GROUP_STATUS_FILTER_ALL = 0; //所有列表(即不过滤)
	const GROUP_STATUS_FILTER_LEAVE_FOR_SUCCESSION = 1; //离职待继承
	const GROUP_STATUS_FILTER_LEAVE_IN_SUCCESSION = 2; //离职继承中
	const GROUP_STATUS_FILTER_LEAVE_AND_SUCCESSION = 3; //离职继承完成

	/**
	 * 获取客户群列表
	 * @param int $status_filter
	 * @param array $owner_user_id_list
	 * @param int $limit
	 * @param null $cursor
	 * @return array [ [[chat_id=>chat_id, status=>status], ...], next_cursor]
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 * @see https://open.work.weixin.qq.com/api/doc/90001/90143/93414
	 */
	public static function getGroupList($status_filter = self::GROUP_STATUS_FILTER_ALL, $owner_user_id_list = [], $limit = 1000, $cursor = null){
		$url = '/cgi-bin/externalcontact/groupchat/list';
		$param = [
			'status_filter' => $status_filter,
			'limit'         => $limit,
		];
		if($owner_user_id_list){
			$param['owner_filter']['userid_list'] = $owner_user_id_list;
		}
		if($cursor){
			$param['cursor'] = $cursor;
		}
		$rsp = self::sendRequest($url, $param, true);
		$rsp->assertSuccess();
		return [$rsp->get('group_chat_list'), $rsp->get('next_cursor')];
	}

	/**
	 * @param $chat_id
	 * @return \LFPhp\WeworkSdk\Base\Response
	 * {
	 * "errcode": 0,
	 * "errmsg": "ok",
	 * "group_chat": {
	 * "chat_id": "wrQQm6DgAANS6TJzuihijXVujzjpI46w",
	 * "name": "外部群",
	 * "owner": "richardfeng",
	 * "create_time": 1605171723,
	 * "member_list": [
	 * {
	 * "userid": "lexi",
	 * "type": 1,
	 * "join_time": 1605171723,
	 * "join_scene": 1
	 * },
	 * {
	 * "userid": "richardfeng",
	 * "type": 1,
	 * "join_time": 1605171723,
	 * "join_scene": 1
	 * },
	 * {
	 * "userid": "sasumihuang",
	 * "type": 1,
	 * "join_time": 1605171723,
	 * "join_scene": 1
	 * },
	 * {
	 * "userid": "wmQQm6DgAAp9Xw4C2OStSWjpf1CCshnQ",
	 * "type": 2,
	 * "join_time": 1605171723,
	 * "join_scene": 1,
	 * "unionid": "ozStBtx7SEDR9Z7yvFleHV2LTAcE"
	 * }
	 * ]
	 * }
	 * }
	 * @see https://work.weixin.qq.com/api/doc/90001/90143/92707
	 */
	public static function getInfo($chat_id){
		$url = '/cgi-bin/externalcontact/groupchat/get';
		$params = [
			'chat_id'   => $chat_id,
			'need_name' => 1,
		];
		return self::sendRequest($url, $params);
	}

	/**
	 * 按自然日聚合的方式 统计群数据
	 * https://work.weixin.qq.com/api/doc/90000/90135/92133#%E6%8C%89%E8%87%AA%E7%84%B6%E6%97%A5%E8%81%9A%E5%90%88%E7%9A%84%E6%96%B9%E5%BC%8F
	 * @param $day_begin_time
	 * @param $day_end_time
	 * @param $owner_list
	 * @return \LFPhp\WeworkSdk\Base\Response
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 */
	public static function statisticByDay($day_begin_time, $day_end_time, $owner_list){
		$url = 'cgi-bin/externalcontact/groupchat/statistic_group_by_day';
		$params = [
			'day_begin_time' => $day_begin_time,
			'day_end_time'   => $day_end_time,
			'owner_filter'   => [
				'userid_list' => $owner_list,
			],
		];
		return self::sendRequest($url, $params);
	}

	/**
	 * 按群主聚合的方式 统计群数据
	 * https://open.work.weixin.qq.com/api/doc/90000/90135/92133
	 * @param $day_begin_time
	 * @param $day_end_time
	 * @param $owner_list
	 * @param $limit
	 * @param $offset
	 * @return \LFPhp\WeworkSdk\Base\Response
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 */
	public static function statisticByOwnerList($day_begin_time, $day_end_time, $owner_list, $limit = 500, $offset = 0){
		$url = 'cgi-bin/externalcontact/groupchat/statistic';
		$params = [
			'day_begin_time' => $day_begin_time,
			'day_end_time'   => $day_end_time,
			'owner_filter'   => [
				'userid_list' => $owner_list,
			],
			'limit'          => $limit,
			'offset'         => $offset,
		];
		//需要用post方法
		$rsp = self::sendRequest($url, $params, true);
		$result = $rsp->jsonSerialize();
		return $result['data'];
	}
}
