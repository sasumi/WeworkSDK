<?php

namespace LFPhp\WeworkSdk\Service;

use LFPhp\WeworkSdk\Base\Service;
use LFPhp\WeworkSdk\Exception\ConnectException;

class Auth extends Service {
	/**
	 * 获取预授权码
	 * @param $suite_access_token
	 * @return string
	 */
	public static function getPreAuthCode($suite_access_token){
		$url = '/cgi-bin/service/get_pre_auth_code?suite_access_token='.$suite_access_token;
		$params = [
			'suite_access_token' => $suite_access_token,
		];
		$rsp = self::sendRequest($url, $params, false);
		$rsp->assertSuccess();
		return $rsp->get('pre_auth_code');
	}

	/**
	 * https://open.work.weixin.qq.com/api/doc/90001/90142/90593
	 * @param $corp_id
	 * @param $provider_secret
	 * @return string
	 */
	public static function getProviderToken($corp_id, $provider_secret){
		$url = '/cgi-bin/service/get_provider_token';
		$param = [
			"corpid"          => $corp_id,
			"provider_secret" => $provider_secret,
		];
		$rsp = self::sendRequest($url, $param);
		$rsp->assertSuccess();
		return $rsp->data['provider_access_token'];
	}

	/**
	 * @param $suite_access_token
	 * @param $auth_code
	 * @return \LFPhp\WeworkSdk\Base\Response
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 */
	public static function getPermanentCode($suite_access_token, $auth_code){
		$url = '/cgi-bin/service/get_permanent_code?suite_access_token='.$suite_access_token;
		$param = [
			"auth_code" => $auth_code,
		];
		$rsp = self::sendRequest($url, $param);
		$rsp->assertSuccess();
		return $rsp;
	}

	/**
	 * 获取企业的AccessToken
	 * https://open.work.weixin.qq.com/api/doc/10975#%E8%8E%B7%E5%8F%96%E4%BC%81%E4%B8%9Aaccess_token
	 * 此处获得的企业access_token与企业获取access_token拿到的token，本质上是一样的，只不过获取方式不同。获取之后，就跟普通企业一样使用token调用API接口,
	 * 与这个接口获取的accessToken一样
	 * https://open.work.weixin.qq.com/api/doc/10013#%E7%AC%AC%E4%B8%89%E6%AD%A5%EF%BC%9A%E8%8E%B7%E5%8F%96access_token
	 * @param $auth_corp_id
	 * @param $suite_access_token
	 * @param $permanent_code
	 * @return array [access_token, expires_in超时时间（秒）]
	 */
	public static function getAccessToken($auth_corp_id, $suite_access_token, $permanent_code){
		$url = '/cgi-bin/service/get_corp_token?suite_access_token='.$suite_access_token;
		$param = [
			"auth_corpid"    => $auth_corp_id,
			"permanent_code" => $permanent_code,
		];
		$rsp = self::sendRequest($url, $param);
		$rsp->assertSuccess();
		return [$rsp->get('access_token'), $rsp->get('expires_in')];
	}

	/**
	 * 通过corp id 和 corp secret获取access token
	 * @param $corp_id
	 * @param $corp_secret
	 * @return array
	 * @throws ConnectException
	 */
	public static function getAccessTokenByCorpSecret($corp_id, $corp_secret){
		$url = '/cgi-bin/gettoken';
		$param = [
			"corpid"     => $corp_id,
			"corpsecret" => $corp_secret,
		];
		$rsp = self::sendRequest($url, $param, false);
		$rsp->assertSuccess();
		return [$rsp->get('access_token'), $rsp->get('expires_in')];
	}

	/**
	 * @param $suite_access_token
	 * @param $pre_auth_code
	 * @param int $auth_type
	 * @throws ConnectException
	 */
	public static function setSessionInfo($suite_access_token, $pre_auth_code, $auth_type = 0){
		$url = 'https://qyapi.weixin.qq.com/cgi-bin/service/set_session_info?suite_access_token='.$suite_access_token;
		$param = [
			'pre_auth_code' => $pre_auth_code,
			'session_info'  => [
				"auth_type" => $auth_type,
			],
		];
		$rsp = self::sendRequest($url, $param);
		$rsp->assertSuccess();
	}

	/**
	 * @return string
	 * @todo
	 */
	public static function buildAuthUrl(){
		$query = [
			//			'suite_id' => $this->getSuiteId(),
			//			'pre_auth_code' => $code,
			//			'redirect_uri' => urlencode(trim(env('WEWORK_AUTH_CALLBACK_URL')).$app_id),
			//			'state' => $this->getAuthUrlState(),
		];
		$base_uri = 'https://open.work.weixin.qq.com/3rdapp/install?'.http_build_query($query);
		return $base_uri;
	}

	/**
	 * 获取用户登录信息
	 * @param $provider_access_token
	 * @param $auth_code
	 * @return mixed
	 */
	public static function getLoginInfo($provider_access_token, $auth_code){
		$url = '/cgi-bin/service/get_login_info?access_token='.$provider_access_token;
		$param = [
			"auth_code" => $auth_code,
		];

		$rsp = self::sendRequest($url, $param);
		$rsp->assertSuccess();
		return $rsp->data;
	}

	/**
	 * 三种Token的区别：
	 * https://open.work.weixin.qq.com/api/doc/90001/90142/90593
	 * https://open.work.weixin.qq.com/api/doc/10975#%E8%8E%B7%E5%8F%96%E7%AC%AC%E4%B8%89%E6%96%B9%E5%BA%94%E7%94%A8%E5%87%AD%E8%AF%81
	 * 获取suite_access_token
	 * @param $suite_id
	 * @param $suite_secret
	 * @param $suite_ticket
	 * @return array [$suite_access_token, $expires_in]
	 */
	public static function getSuiteAccessToken($suite_id, $suite_secret, $suite_ticket){
		$url = '/cgi-bin/service/get_suite_token';
		$param = [
			"suite_id"     => $suite_id,
			"suite_secret" => $suite_secret,
			"suite_ticket" => $suite_ticket,
		];
		$rsp = self::sendRequest($url, $param);
		$rsp->assertSuccess();
		$suite_access_token = $rsp->get('suite_access_token');
		$expires_in = $rsp->get('expires_in');
		return [$suite_access_token, $expires_in];
	}

	/**
	 * 获取应用的管理员列表
	 * @param string $corp_id
	 * @param string $agent_id
	 * @param string $suite_access_token
	 * @return mixed
	 */
	public static function getAdminList($corp_id, $agent_id, $suite_access_token){
		$url = '/cgi-bin/service/get_admin_list?suite_access_token='.$suite_access_token;
		$param = [
			'auth_corpid' => $corp_id,
			'agentid'     => $agent_id,
		];
		$rsp = self::sendRequest($url, $param);
		$rsp->assertSuccess();
		return $rsp->get('admin');
	}

	/**
	 * 网页授权，获取访问用户身份
	 * https://work.weixin.qq.com/api/doc/90001/90143/91121
	 * @param $suite_access_token
	 * @param $code
	 * @return array
	 *  [
	 *      corp_id => 用户所属企业的corpid
	 *      user_id => 用户在企业内的UserID，如果该企业与第三方应用有授权关系时，返回明文UserId，否则返回密文UserId
	 *      device_id => 手机设备号(由企业微信在安装时随机生成，删除重装会改变，升级不受影响)
	 *      user_ticket => 成员票据，scope为snsapi_userinfo或snsapi_privateinfo，可以获取用户信息或敏感信息
	 *      expires_in => user_ticket的有效时间（秒
	 *      open_userid => 全局唯一。对于同一个服务商，不同应用获取到企业内同一个成员的open_userid是相同的
	 * ]
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 */
	public static function getUserinfo3rd($suite_access_token, $code){
		$url = '/cgi-bin/service/getuserinfo3rd?suite_access_token='.$suite_access_token;
		$params = [
			'code' => $code,
		];
		$rsp = self::sendRequest($url, $params, false);
		$rsp->assertSuccess();
		$data = [
			'corp_id'     => $rsp->get('CorpId'),
			'user_id'     => $rsp->get('UserId'),
			'device_id'   => $rsp->get('DeviceId'),
			'user_ticket' => $rsp->get('user_ticket'),
			'expires_in'  => $rsp->get('expires_in'),
			'open_userid' => $rsp->get('open_userid'),
		];
		return $data;
	}

	/**
	 * 获取用户敏感信息
	 * https://work.weixin.qq.com/api/doc/90001/90143/91122
	 * @param $suite_access_toekn
	 * @param $user_ticket
	 * @return array
	 * [
	 *      corp_id => 用户所属企业的corpid
	 *      user_id => 成员UserID
	 *      user_name => 成员姓名，2020年6月30日起，对所有历史第三方应用不再返回，第三方页面需要通过通讯录展示组件来展示名字
	 *      gender => 性别
	 *      avatar => 头像url
	 *      qr_code => 员工个人二维码（扫描可添加为外部联系人），仅在用户同意snsapi_privateinfo授权时返回
	 * ]
	 * @throws ConnectException
	 */
	public static function getuserdetail3rd($suite_access_toekn, $user_ticket){
		$url = '/cgi-bin/service/getuserdetail3rd?suite_access_token='.$suite_access_toekn;
		$params = [
			'user_ticket' => $user_ticket,
		];
		$rsp = self::sendRequest($url, $params);
		$rsp->assertSuccess();
		$data = [
			'corp_id'   => $rsp->get('corpid'),
			'user_id'   => $rsp->get('userid'),
			'user_name' => $rsp->get('name'),
			'gender'    => $rsp->get('gender'),
			'avatar'    => $rsp->get('avatar'),
			'qr_code'   => $rsp->get('qr_code'),
		];
		return $data;
	}

	/**
	 * 网页授权，获取访问用户身份
	 * https://work.weixin.qq.com/api/doc/90000/90135/91023
	 * @param $access_token
	 * @param $code
	 * @return array
	 *  [
	 *      user_id => 用户在企业内的UserID，如果该企业与第三方应用有授权关系时，返回明文UserId，否则返回密文UserId
	 *      device_id => 手机设备号(由企业微信在安装时随机生成，删除重装会改变，升级不受影响)
	 *      open_id => 非企业成员的标识，对当前企业唯一。不超过64字节
	 *      external_userid => 外部联系人id，当且仅当用户是企业的客户，且跟进人在应用的可见范围内时返回。如果是第三方应用调用，针对同一个客户，同一个服务商不同应用获取到的id相同
	 * ]
	 */
	public static function getUserInfo($access_token, $code){
		$url = '/cgi-bin/user/getuserinfo';
		$params = [
			'access_token' => $access_token,
			'code'         => $code,
		];
		$rsp = self::sendRequest($url, $params, false);
		$rsp->assertSuccess();
		return [
			'user_id'         => $rsp->get('UserId'),
			'device_id'       => $rsp->get('DeviceId'),
			'open_id'         => $rsp->get('OpenId'),
			'external_userid' => $rsp->get('external_userid'),
		];
	}

	/**
	 * 获取应用详情
	 * https://work.weixin.qq.com/api/doc/90001/90143/90363
	 * @param $access_token
	 * @param $agentid
	 * @return \LFPhp\WeworkSdk\Base\Response
	 * @throws ConnectException
	 */
	public static function getAgentDetail($access_token, $agentid){
		$url = '/cgi-bin/agent/get?access_token='.$access_token;
		$params = [
			'agentid' => $agentid,
		];
		$rsp = self::sendRequest($url, $params, false);
		$rsp->assertSuccess();

		return $rsp->data;
	}

	/**
	 * 获取企业授权信息
	 * https://work.weixin.qq.com/api/doc/90001/90143/90604
	 * @param $suite_access_token
	 * @param $auth_corp_id
	 * @param $permanent_code
	 * @return mixed|null
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 */
	public static function getAuthInfo($suite_access_token, $auth_corp_id, $permanent_code){
		$url = '/cgi-bin/service/get_auth_info?suite_access_token='.$suite_access_token;
		$params = [
			'auth_corpid'    => $auth_corp_id,
			'permanent_code' => $permanent_code,
		];
		$rsp = self::sendRequest($url, $params);
		$rsp->assertSuccess();

		return $rsp->data;
	}

	/**
	 * 推广二维码
	 * https://open.work.weixin.qq.com/api/doc/11729
	 * @param $provider_access_token
	 * @param $template_id
	 * @param string $state 标示
	 * @return mixed|null
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 */
	public static function getRegisterCode($provider_access_token, $template_id, $state){
		$url = '/cgi-bin/service/get_register_code?provider_access_token='.$provider_access_token;
		$params = [
			'provider_access_token' => $provider_access_token,
			'template_id'           => $template_id,
			'state'                 => $state,
		];
		$rsp = self::sendRequest($url, $params);
		$rsp->assertSuccess();

		return $rsp->data;
	}

	/**
	 * 注册码注册成功，解除通讯录锁定
	 * @param string $access_token 注册成功后返回的access_token
	 * @return bool
	 * @throws ConnectException
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public static function contactSyncSuccess($access_token){
		$url = '/cgi-bin/sync/contact_sync_success?access_token='.$access_token;
		$params = [
			'access_token' => $access_token,
		];

		$rsp = self::sendRequest($url, $params, false);
		$rsp->assertSuccess();

		return true;
	}

	/**
	 * corp_id转换
	 * @see https://open.work.weixin.qq.com/api/doc/90001/90143/95327#1.4%20corpid%E8%BD%AC%E6%8D%A2
	 * @param $access_token
	 * @param $corp_id
	 * @return string
	 * @throws ConnectException
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public static function toOpenCorpId($access_token, $corp_id){
		$url = '/cgi-bin/corp/to_open_corpid?access_token='.$access_token;
		$params = [
			'corpid'       => $corp_id,
			'access_token' => $access_token,
		];
		$rsp = self::sendRequest($url, $params);
		$rsp->assertSuccess();
		return $rsp->get('open_corpid');
	}
}
