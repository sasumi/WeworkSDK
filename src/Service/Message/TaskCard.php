<?php
namespace LFPhp\WeworkSdk\Service\Message;

use LFPhp\WeworkSdk\Base\AuthorizedService;

/**
 * Class TaskCard
 * @package LFPhp\WeworkSdk\Service\Message
 * @link https://open.work.weixin.qq.com/api/doc/90001/90143/91585
 */
class TaskCard extends AuthorizedService {
	/**
	 * @param string $agent_id
	 * @param string $task_id
	 * @param string[] $user_ids
	 * @param string $clicked_key
	 * @return array
	 * @throws \LFPhp\WeworkSdk\Exception\ConnectException
	 */
	public static function updateTaskCard($agent_id, $task_id, array $user_ids, $clicked_key){
		$uri = "/cgi-bin/message/update_taskcard";
		$param = [
			"userids"     => $user_ids,
			"agentid"     => $agent_id,
			"task_id"     => $task_id,
			"clicked_key" => $clicked_key,
		];
		$rsp = self::sendRequest($uri, $param);
		$rsp->assertSuccess();
		return $rsp->get('invaliduser');
	}
}
