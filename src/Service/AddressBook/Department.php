<?php

namespace LFPhp\WeworkSdk\Service\AddressBook;

use LFPhp\WeworkSdk\Base\AuthorizedService;

class Department extends AuthorizedService {
	/**
	 * 获取部门列表
	 * @param string $department_id 部门id。获取指定部门及其下的子部门。 如果不填，默认获取全量组织架构
	 * @return array 返回格式请参考官方接口定义
	 * @throws \Exception
	 * @see https://work.weixin.qq.com/api/doc/90001/90143/90344
	 */
	public static function getList($department_id = null){
		$url = '/cgi-bin/department/list';
		$param = [
			'id' => $department_id,
		];
		$rsp = self::getJson($url, $param);
		$rsp->assertSuccess();
		return $rsp->get('department');
	}

	/**
	 * 创建部门
	 * @param array $data
	 * @return int 部门ID
	 */
	public static function create(array $data){
		$url = '/cgi-bin/department/create';
		$param = [
			'name'      => $data['name'], //部门名称。同一个层级的部门名称不能重复。长度限制为1~32个字符，字符不能包括\:?”<>｜
			'name_en'   => $data['name_en'], //英文名称。同一个层级的部门名称不能重复。需要在管理后台开启多语言支持才能生效。长度限制为1~32个字符，字符不能包括\:?”<>｜
			'parent_id' => $data['parent_id'],
			'order'     => $data['order'], //在父部门中的次序值。order值大的排序靠前。有效的值范围是[0, 2^32)
			'id'        => $data['id'], //部门id，32位整型，指定时必须大于1。若不填该参数，将自动生成id
		];
		$rsp = self::postJson($url, $param);
		$rsp->assertSuccess();
		return $rsp->get('id');
	}

	/**
	 * 更新部门
	 * @param array $data
	 * @return bool
	 */
	public static function update(array $data){
		$url = '/cgi-bin/department/update';
		$param = [
			'name'      => $data['name'],
			'name_en'   => $data['name_en'],
			'parent_id' => $data['parent_id'],
			'order'     => $data['order'],
			'id'        => $data['id'],
		];
		$rsp = self::postJson($url, $param);
		$rsp->assertSuccess();
		return true;
	}

	/**
	 * 删除部门
	 * @param $department_id
	 * @return bool
	 */
	public static function delete($department_id){
		$url = '/cgi-bin/department/delete';
		$param = [
			'id' => $department_id,
		];
		$rsp = self::postJson($url, $param);
		$rsp->assertSuccess();
		return true;
	}
}
