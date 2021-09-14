<?php

namespace system;

/**
 * Class MyResponse
 * @author Ing. Pavol Horvath
 */
class Response
{
	/**
	 * @var mixed
	 */
	private $response = '';

	/**
	 * @param mixed|null $response
	 *
	 * @return $this
	 */
	public function json ($response = null):Response
	{
		header('Content-Type: application/json');
		if ($response === null && $this->response) {
			$response = $this->response;
		}
		$this->response = json_encode($response);

		return $this;
	}

	/**
	 * @param mixed $msg
	 *
	 * @return $this
	 */
	public function unauthorized ($msg = 'Unauthorized'):Response
	{
		http_response_code(401);
		$this->response = $msg;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function send ()
	{
		return $this->response;
	}

	/**
	 * @return $this
	 */
	public function badRequest (array $msgs):Response
	{
		header('Content-Type: application/json');
		$response = ['errors' => $msgs];
		$this->response = json_encode($response);

		return $this;
	}

	/**
	 * @return Response
	 */
	public static function init ():Response
	{
		return new Response();
	}
}