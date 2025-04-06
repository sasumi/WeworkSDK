<?php

namespace LFPhp\WeworkSdk\Service\ExternalContact;

use LFPhp\WeworkSdk\Base\AuthorizedService;

/**
 * 群欢迎语素材（模板）
 * @package LFPhp\WeworkSdk\Service\External
 * @see https://open.work.weixin.qq.com/api/doc/90000/90135/92366
 */
class WelcomeMessageGroupTemplate extends AuthorizedService {
	/**
	 * 添加模板
	 * 企业可通过此API向企业的群欢迎语素材库中添加素材。每个企业最多可同时配置20个群欢迎语素材。
	 * @param $template_info
	 * @return string template id
	 */
	public static function addTemplate($template_info){
		$url = '/cgi-bin/externalcontact/group_welcome_template/add';
		$rsp = self::postJson($url, $template_info);
		$rsp->assertSuccess();
		return $rsp->get('template_id');
	}

	/**
	 * 编辑模板
	 * @param $template_id
	 * @param $template_info
	 */
	public static function editTemplate($template_id, $template_info){
		$template_info['template_id'] = $template_id;
		$url = '/cgi-bin/externalcontact/group_welcome_template/edit';
		$rsp = self::postJson($url, $template_info);
		$rsp->assertSuccess();
	}

	/**
	 * 获取欢迎语模板信息
	 * @param string $template_id
	 * @return object
	 */
	public static function getTemplateInfo($template_id){
		$url = '/cgi-bin/externalcontact/group_welcome_template/edit';
		$rsp = self::postJson($url, ['template_id' => $template_id]);
		$rsp->assertSuccess();
		return $rsp->data;
	}

	/**
	 * 删除入群欢迎语
	 * @param $template_id
	 * @param $agentid
	 * @return \LFPhp\WeworkSdk\Base\Response
	 */
	public static function delTemplate($template_id, $agentid = null){
		$params['template_id'] = $template_id;
		$params['agentid'] = $agentid;
		$url = '/cgi-bin/externalcontact/group_welcome_template/del';
		$rsp = self::postJson($url, $params);
		return $rsp;
	}
}
