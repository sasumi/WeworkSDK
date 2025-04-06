<?php

namespace LFPhp\WeworkSdk\Service\AddressBook;

use Exception;
use LFPhp\WeworkSdk\Base\AuthorizedService;

class Tag extends AuthorizedService {
	/**
	 * 获取标签列表
	 * @return array 格式如：[tag_id=>1, tag_name=>'hello'], ...]
	 */
	public static function getList(){
		$url = '/cgi-bin/tag/list';
		$rsp = self::sendRequest($url, [], false);
		$rsp->assertSuccess();
		$list = $rsp->get('taglist');
		if($list){
			$ret = [];
			foreach($list as $item){
				$ret[] = ['tag_id' => $item['tagid'], 'tag_name' => $item['tagname']];
			}
			return $ret;
		}
		return [];
	}

	/**
	 * 创建标签
	 * @param string $name 标签名称，长度限制为32个字以内（汉字或英文字母），标签名不可与其他标签重名。
	 * @param null $tag_id 标签id，非负整型，指定此参数时新增的标签会生成对应的标签id，不指定时则以目前最大的id自增。最大3000个
	 * @return int 创建成功标签ID
	 */
	public static function create($name, $tag_id = null){
		$url = '/cgi-bin/tag/create';
		$param = [
			'tagname' => $name,
			'tagid'   => $tag_id,
		];
		$rsp = self::sendRequest($url, $param, false);
		$rsp->assertSuccess();
		return $rsp->get('tagid');
	}

	/**
	 * 更新标签
	 * @param $name
	 * @param null $tag_id
	 * @return bool
	 */
	public static function update($name, $tag_id = null){
		$url = '/cgi-bin/tag/update';
		$param = [
			'tagname' => $name,
			'tagid'   => $tag_id,
		];
		$rsp = self::sendRequest($url, $param, false);
		$rsp->assertSuccess();
		return true;
	}

	/**
	 * 删除标签
	 * @param $tag_id
	 * @return bool
	 */
	public static function delete($tag_id){
		$url = '/cgi-bin/tag/create';
		$param = [
			'tagid' => $tag_id,
		];
		$rsp = self::sendRequest($url, $param, false);
		$rsp->assertSuccess();
		return true;
	}

	/**
	 * 获取指定标签下的成员列表
	 * @param $tag_id
	 * @return array
	 */
	public static function getUserList($tag_id){
		$url = '/cgi-bin/tag/get';
		$param = [
			'tagid' => $tag_id,
		];
		$rsp = self::sendRequest($url, $param, false);
		$rsp->assertSuccess();
		return [
			'tag_name'      => $rsp->get('tagname'),
			'user_list'     => $rsp->get('userlist'), //格式为：[{userid:1, name:'李四'},...]
			'party_id_list' => $rsp->get('partylist'), //格式为：[2,...]
		];
	}

	/**
	 * 用户列表打标签
	 * @param int $tag_id
	 * @param array $user_list 格式为：[ "user1","user2"]
	 * @param array $party_list 格式为：[2]
	 * @return array|bool true为全部成功，如array，则为部分成功
	 */
	public static function markUserList($tag_id, array $user_list = [], array $party_list = []){
		if(!$user_list && !$party_list){
			throw new Exception('User list or party list required');
		}
		return self::_markUserList('/cgi-bin/tag/addtagusers', $tag_id, $user_list, $party_list);
	}

	/**
	 * 用户列表删除标签
	 * @param int $tag_id
	 * @param array $user_list 格式为：[ "user1","user2"]
	 * @param array $party_list 格式为：[2]
	 * @return array|bool true为全部成功，如array，则为部分成功
	 */
	public static function unMarkUserList($tag_id, array $user_list = [], array $party_list = []){
		if(!$user_list && !$party_list){
			throw new Exception('User list or party list required');
		}
		return self::_markUserList('/cgi-bin/tag/deltagusers', $tag_id, $user_list, $party_list);
	}

	private static function _markUserList($url, $tag_id, array $user_list = [], array $party_list = []){
		if(!$user_list && !$party_list){
			throw new Exception('User list or party list required');
		}
		$param = [
			'tagid'     => $tag_id,
			'userlist'  => $user_list,
			'partylist' => $party_list,
		];
		$rsp = self::sendRequest($url, $param, false);
		$rsp->assertSuccess();

		$invalid_list = $rsp->get('invalidlist');
		$invalid_list = $invalid_list ? explode('|', $invalid_list) : [];
		$invalid_party = $rsp->get('invalidparty');

		if(!$invalid_list && !$invalid_party){
			return true;
		}

		//部分成功
		//格式为 [[usr1, usr2,...], [2,4,...]]
		return [$invalid_list, $invalid_party];
	}
}
