<?php

namespace oddEvan\FetchEvaluation;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

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
		$id = Uuid::fromString($arguments['id']);
		$points = $this->repo->getPointsForReceipt($id);

		$response->getBody()->write(json_encode(['points' => $points]));
		return $response;
	}
}
