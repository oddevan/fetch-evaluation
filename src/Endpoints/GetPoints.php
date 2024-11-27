<?php

namespace oddEvan\FetchEvaluation\Endpoints;

use oddEvan\FetchEvaluation\ReceiptRepo;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use Throwable;

/**
 * Get the points earned by the given receipt.
 */
class GetPoints {
	/**
	 * Construct the service.
	 *
	 * @param ReceiptRepo $repo For retrieving receipt info.
	 */
	public function __construct(private ReceiptRepo $repo) {
	}

	/**
	 * Execute the endpoint.
	 *
	 * @param RequestInterface  $request   Incoming API request.
	 * @param ResponseInterface $response  Outgoing response.
	 * @param array             $arguments Route placeholder arguments.
	 * @return ResponseInterface
	 */
	public function run(RequestInterface $request, ResponseInterface $response, array $arguments): ResponseInterface {
		try {
			$id = Uuid::fromString($arguments['id']);
			$points = $this->repo->getPointsForReceipt($id);

			$response->getBody()->write(json_encode(['points' => $points]));
		} catch (Throwable $e) {
			$response->getBody()->write(\json_encode(['error' => $e->getMessage()]));
			$response = $response->withStatus(404);

			\error_log($e->getMessage() . "\n" . $e->getTraceAsString());
		}
		return $response;
	}
}
