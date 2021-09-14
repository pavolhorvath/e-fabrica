<?php

namespace system;

/**
 * Class Router
 * @author Ing. Pavol Horvath
 */
class Router
{
	/**
	 * @var array
	 */
	private $get = [];

	/**
	 * @var array
	 */
	private $post = [];

	/**
	 * @var string
	 */
	private $requestMethod = '';

	/**
	 * @var string
	 */
	private $requestUri = '';

	/**
	 * load route methodes from custom file
	 * @return $this
	 */
	public function setRouteMethodes ():Router
	{
		$routePath = __DIR__ . '/../app/route.php';
		if (is_file($routePath)) {
			require_once $routePath;

			if (isset($get) && is_array($get)) {
				$this->get = $get;
			}

			if (isset($post) && is_array($post)) {
				$this->post = $post;
			}
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	public function setRequestMethod ():Router
	{
		$methodeWhiteList = [
			'GET',
			'POST',
		];

		if (in_array($_SERVER['REQUEST_METHOD'], $methodeWhiteList)) {
			$this->requestMethod = $_SERVER['REQUEST_METHOD'];
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	public function setRequestUri ():Router
	{
		$requestUri = substr($_SERVER['REQUEST_URI'], 1);
		$requestUri = explode('?', $requestUri)[0];
		$this->requestUri = $requestUri;

		return $this;
	}

	/**
	 * @return false|mixed|string|null
	 */
	public function route ()
	{
		if ($this->requestMethod == 'GET') {
			return $this->routeGet();

		} elseif ($this->requestMethod == 'POST') {
			return $this->routePost();

		} else {
			return $this->route404();
		}
	}

	/**
	 * @return false|mixed|string|null
	 */
	private function routeGet ()
	{
		if (!$this->requestUri && !isset($this->get[$this->requestUri])) {
			$indexPath = __DIR__ . '/../app/index.html';
			if (is_file($indexPath)) {
				return file_get_contents($indexPath);
			} else {
				return '';
			}
		}

		if (!isset( $this->get[ $this->requestUri ] )) {
			return $this->route404();
		}

		$action = explode('@', $this->get[ $this->requestUri ]);
		$controllerName = "app\\controllers\\{$action[0]}Controller";
		$methodeName = $action[1];

		$controller = new $controllerName();
		return $controller->$methodeName();
	}

	/**
	 * @return false|mixed|string|null
	 * @throws ReflectionException
	 */
	private function routePost ()
	{
		if (!isset( $this->post[ $this->requestUri ] )) {
			return $this->route404();
		}

		$requestBody = file_get_contents('php://input');
		$requestBodyData = json_decode($requestBody, true);
		$_POST = array_merge($_POST, $requestBodyData);
		$_REQUEST = array_merge($_REQUEST, $requestBodyData);

		$action = explode('@', $this->post[ $this->requestUri ]);
		$controllerName = "app\\controllers\\{$action[0]}Controller";
		$methodeName = $action[1];

		$rm = new \ReflectionMethod($controllerName, $methodeName);
		$argsToInvoke = [];
		foreach ($rm->getParameters() as $arg) {
			$argsToInvoke[] = $_POST[$arg->getName()] ?? ($arg->isDefaultValueAvailable() ? $arg->getDefaultValue() : null);
		}

		return $rm->invokeArgs( new $controllerName, $argsToInvoke );
	}

	/**
	 * @return false|string|null
	 */
	private function route404 ()
	{
		http_response_code(404);

		$path = __DIR__ . '/../app/404.html';
		if (is_file($path)) {
			return file_get_contents($path);
		}

		return null;
	}

	/**
	 * @return Router
	 */
	public static function init ()
	{
		return new Router();
	}
}