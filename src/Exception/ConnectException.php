<?php

namespace LFPhp\WeworkSdk\Exception;

use Exception;
use Throwable;

class ConnectException extends Exception {
	private $request_url;
	private $request_param;
	private $response_body;

	public function __construct($url, $param, $response_body, $client, Throwable $previous = null){
		$this->request_url = $url;
		$this->request_param = $param;
		$this->response_body = $response_body;
		parent::__construct('WeWork connect error', 0, $previous);
	}
}
