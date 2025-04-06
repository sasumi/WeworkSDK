<?php

namespace LFPhp\WeworkSdk\Service\Audit;

use LFPhp\WeworkSdk\Base\AuthorizedService;

class MsgAudit extends AuthorizedService {

	/**
	 * 获取会话同意情况
	 * @see https://work.weixin.qq.com/api/doc/90000/90135/91614
	 * @param string $type 拉取对应版本的开启成员列表。1表示办公版；2表示服务版；3表示企业版。非必填，不填写的时候返回全量成员列表。
	 * @return array {
	 * "errcode": 0,
	 * "errmsg": "ok",
	 * "ids":[
	 * "userid_111",
	 * "userid_222",
	 * "userid_333",
	 * ],
	 * }
	 */
	public static function getPermitUserList($type = null){
		$url = '/cgi-bin/msgaudit/get_permit_user_list';
		$params = [];
		if(!empty($type)){
			$params['type'] = $type;
		}
		$rsp = self::sendRequest($url, $params);
		if(!$rsp->isSuccess()){
			return [];
		}
		return $rsp->get('ids');
	}

	/**
	 * 获取会话内容存档内部群信息
	 * @see https://work.weixin.qq.com/api/doc/90000/90135/92951
	 * @return array {
	 * "roomname": "蓦然回首",
	 * "creator": "ZhangWenChao",
	 * "room_create_time": 1592361604,
	 * "notice": "",
	 * "members": [
	 * {
	 * "memberid": "ZhangWenChao",
	 * "jointime": 1592361605
	 * },
	 * {
	 * "memberid": "xujinsheng",
	 * "jointime": 1592377076
	 * }
	 * ],
	 * "errcode": 0,
	 * "errmsg": "ok"
	 * }
	 */
	public static function getInternalChatInfo($room_id){
		$url = '/cgi-bin/msgaudit/groupchat/get';
		$rsp = self::sendRequest($url, ['roomid' => $room_id]);
		if(!$rsp->isSuccess()){
			return [];
		}
		return $rsp->data;
	}

	/**
	 * 获取会话同意情况
	 * @param $arr [['corp_user_id' => '11', 'external_user_id' => '111']]
	 * https://open.work.weixin.qq.com/api/doc/90000/90135/91782
	 * {
	 * "errcode": 0,
	 * "errmsg": "ok",
	 * "agreeinfo" : [
	 * {
	 * "status_change_time" : 1562766651,
	 * "userid" : "XuJinSheng",
	 * "exteranalopenid" : "wmeDKaCPAAGdvxciQWxVsAKwV2HxNAAA",
	 * "agree_status":"Agree",
	 * },
	 * {
	 * "status_change_time" : 1562766651,
	 * "userid" : "XuJinSheng",
	 * "exteranalopenid" : "wmeDKaCQAAIQ_p7ACnxksfeBJSGocAAA",
	 * "agree_status":"Disagree",
	 * },
	 * {
	 * "status_change_time" : 1562766651,
	 * "userid" : "XuJinSheng",
	 * "exteranalopenid" : "wmeDKaCwAAIQ_p7ACnxckLBBJSGocAAA",
	 * "agree_status":"Default_Agree",
	 * },
	 * ],
	 * }
	 */
	public static function checkSingleAgree($arr){
		$url = '/cgi-bin/msgaudit/check_single_agree';
		$tmpArr = [];
		foreach($arr as $key => $val){
			$tmpArr[] = [
				'userid'          => $val['corp_user_id'],
				'exteranalopenid' => $val['external_user_id'],
			];
		}
		$params = [
			'info' => $tmpArr,
		];
		$rsp = self::sendRequest($url, $params);
		if(!$rsp->isSuccess()){
			return [];
		}
		return $rsp->get('agreeinfo');
	}

	/**
	 * 获取群会话同意状态
	 * @param string $roomId
	 * @return array|mixed|null
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 */
	public static function checkRoomAgree(string $roomId){
		$url = '/cgi-bin/msgaudit/check_room_agree';
		$params = [
			'roomid' => $roomId,
		];
		$rsp = self::sendRequest($url, $params);
		if(!$rsp->isSuccess()){
			return [];
		}
		return $rsp->get('agreeinfo');
	}
}
