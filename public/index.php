<?php

namespace oddEvan\FetchEvaluation;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (RequestInterface $request, ResponseInterface $response) {
	$response->getBody()->write(\json_encode(['hello' => 'world', 'version' => '1.0']));
	return $response;
});

$app->run();
