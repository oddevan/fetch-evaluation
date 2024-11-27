<?php

namespace oddEvan\FetchEvaluation\Endpoints;

use oddEvan\FetchEvaluation\{PointCalculator, Receipt, ReceiptRepo};
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use Throwable;

/**
 * Process the receipt according to the PointCalculator rules and save it to the repo.
 */
class ProcessReceipt {
	/**
	 * Construct the service.
	 *
	 * @param ReceiptRepo     $repo For persisting receipt info between requests.
	 * @param PointCalculator $calc For calculating points.
	 */
	public function __construct(private ReceiptRepo $repo, private PointCalculator $calc) {
	}

	/**
	 * Execute the endpoint.
	 *
	 * @param RequestInterface  $request  Incoming API request.
	 * @param ResponseInterface $response Outgoing response.
	 * @return ResponseInterface
	 */
	public function run(RequestInterface $request, ResponseInterface $response): ResponseInterface {
		try {
			$receipt = Receipt::fromJson($request->getBody()->__toString());
			$points = $this->calc->pointsForReceipt($receipt);
			$id = Uuid::uuid7();

			$this->repo->saveReceipt(
				id: $id,
				receipt: $receipt,
				points: $points,
			);

			$response->getBody()->write(\json_encode(['id' => $id]));
		} catch (Throwable $e) {
			$response->getBody()->write(\json_encode(['error' => $e->getMessage()]));
			$response = $response->withStatus(400);
		}
		return $response;
	}
}
