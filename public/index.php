<?php

use system\EnvLoader;
use system\Router;

session_start();

require_once __DIR__ . '/../system/autoload.php';

EnvLoader::init()->loadEnvFile();

$response = Router::init()
	->setRouteMethodes()
	->setRequestMethod()
	->setRequestUri()
	->route();

echo $response;