<?php
namespace LFPhp\WeworkSdk\Service\AddressBook;

use Exception;
use LFPhp\Logger\Logger;
use LFPhp\WeworkSdk\Base\Service;

/**
 * 企业员工（成员）搜索类
 */
class Search extends Service {
	const QUERY_USER_ONLY = 1;
	const QUERY_DEPARTMENT_ONLY = 2;
	const QUERY_BOTH = 0;

	//如果需要精确匹配用户名称或者部门名称或者英文名，不填则默认为模糊匹配；1：匹配用户名称或者部门名称 2：匹配用户英文名
	const FIELD_MATCH_BOTH = 1; //匹配用户名称或者部门名称
	const FIELD_MATCH_ENNAME = 2;

	private static $corp_id;
	private static $provider_access_token;
	private static $agent_id = 0;

	/**
	 * 绑定查询所需环境信息
	 * @param $provider_access_token
	 * @param $corp_id
	 * @param int $agent_id agentid为0则返回该服务商授权通讯录权限范围内的用户信息或者部门信息，否则返回指定agentid应用可见范围内的信息
	 */
	public static function bindCorpInfo($provider_access_token, $corp_id, $agent_id = 0){
		self::$corp_id = $corp_id;
		self::$provider_access_token = $provider_access_token;
		self::$agent_id = $agent_id;
	}

	/**
	 * 通讯录批量搜索
	 * @description 由于单个搜索结果与批量搜索一致，这里建议使用批量搜索来支持，批量搜索能力更强。
	 * @param string $keyword
	 * @param int $start
	 * @param int $size
	 * @param int $query_type
	 * @param int $match_type
	 * @return array [is_last, user_id_list, open_user_id_list, party_id_list]
	 * @see https://work.weixin.qq.com/api/doc/90001/90143/91844
	 */
	public static function contactSearch($keyword, $start = 0, $size = 50, $query_type = self::QUERY_BOTH, $match_type = null){
		$logger = logger::instance(__CLASS__);
		if(!self::$provider_access_token){
			throw new Exception('Provider access token required');
		}
		if(!self::$corp_id){
			throw new Exception('Corp ID required');
		}
		if(!$keyword){
			throw new Exception('Keyword required');
		}

		$url = '/cgi-bin/service/contact/search?provider_access_token='.self::$provider_access_token;
		$param = [
			'auth_corpid'       => self::$corp_id,
			'agentid'           => self::$agent_id,
			'query_result_list' => [
				'query_word'       => $keyword,
				'query_type'       => $query_type,
				'offset'           => $start,
				'limit'            => $size,
				'full_match_field' => $match_type,
				'party_id'         => '1',
			],
		];

		$logger->info("Address Book Param: ", $url, json_encode($param), "\r\n");

		$rsp = self::postJson($url, $param);
		$rsp->assertSuccess();

		$is_last = $rsp->get('query_result_list.is_last');
		$user_id_list = $rsp->get('query_result_list.query_result.user.userid', []);
		$open_user_id_list = $rsp->get('query_result_list.query_result.user.open_userid', []);
		$party_id_list = $rsp->get('query_result_list.query_result.party.department_id', []);
		return [
			'is_last'           => $is_last,
			'user_id_list'      => $user_id_list,
			'open_user_id_list' => $open_user_id_list,
			'party_id_list'     => $party_id_list,
		];
	}

	/**
	 * 员工列表搜索
	 * @param $keyword
	 * @param int $start
	 * @param int $size
	 * @param int $query_type
	 * @param null $match_type
	 * @return array
	 */
	public static function contactSingleSearch($keyword, $start = 0, $size = 50, $query_type = self::QUERY_BOTH, $match_type = null){
		$logger = logger::instance(__CLASS__);
		if(!self::$provider_access_token){
			throw new Exception('Provider access token required');
		}
		if(!self::$corp_id){
			throw new Exception('Corp ID required');
		}
		if(!$keyword){
			throw new Exception('Keyword required');
		}

		$url = '/cgi-bin/service/contact/search?provider_access_token='.self::$provider_access_token;
		$param = [
			'auth_corpid' => self::$corp_id,
			'agentid'     => self::$agent_id,
			'query_word'  => $keyword,
			'query_type'  => $query_type,
			'offset'      => $start,
			'limit'       => $size,
		];
		$rsp = self::postJson($url, $param);
		$rsp->assertSuccess();
		$is_last = $rsp->get('is_last');
		$user_id_list = $rsp->get('query_result.user.userid', []);
		$open_user_id_list = $rsp->get('query_result.user.open_userid', []);
		$department_id_list = $rsp->get('query_result.party.department_id', []);
		return [
			'is_last'            => $is_last,
			'user_id_list'       => $user_id_list,
			'open_user_id_list'  => $open_user_id_list,
			'department_id_list' => $department_id_list,
		];
	}
}
