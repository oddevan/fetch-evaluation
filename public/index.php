<?php

namespace oddEvan\FetchEvaluation;

use oddEvan\FetchEvaluation\Endpoints\GetPoints;
use oddEvan\FetchEvaluation\Endpoints\ProcessReceipt;
use oddEvan\FetchEvaluation\Rules\{ItemCount, ItemDescriptions, PurchaseDay, PurchaseTime, RetailerName, TotalAmount};
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$repo = new ReceiptRepo('sqlite:///' . __DIR__ . '/../data.sqlite');

$app = AppFactory::create();

$app->get('/', function (RequestInterface $request, ResponseInterface $response) {
	$response->getBody()->write(\json_encode(['hello' => 'world', 'version' => '1.0']));
	return $response;
});

$app->post(
	'/receipts/process',
	fn($request, $response) => (new ProcessReceipt(
		repo: $repo,
		calc: new PointCalculator([
			new ItemCount(),
			new ItemDescriptions(),
			new PurchaseDay(),
			new PurchaseTime(),
			new RetailerName(),
			new TotalAmount(),
		])
	))->run($request, $response)
);

$app->get(
	'/receipts/{id}/points',
	fn($request, $response, $arguments) => (new GetPoints(repo: $repo))->run($request, $response, $arguments)
);

$app->run();
