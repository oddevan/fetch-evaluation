<?php // phpcs:ignore PSR1.Files.SideEffects.FoundWithSymbols

namespace oddEvan\FetchEvaluation;

require_once __DIR__ . '/examples/ReceiptExamples.php';

use Laminas\Diactoros\Request;
use Laminas\Diactoros\Response;
use oddEvan\FetchEvaluation\Endpoints\GetPoints;
use oddEvan\FetchEvaluation\Endpoints\ProcessReceipt;
use oddEvan\FetchEvaluation\Rules\{ItemCount, ItemDescriptions, PurchaseDay, PurchaseTime, RetailerName, TotalAmount};
use oddEvan\FetchEvaluation\Test\ReceiptExamples;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Test the two endpoints in succession.
 */
final class EndpointTest extends TestCase {
	/**
	 * Create the database file.
	 *
	 * @return void
	 */
	public static function setUpBeforeClass(): void {
		touch(__DIR__ . '/testdb.sqlite');
	}

	private ReceiptRepo $repo;

	protected function setUp(): void {
		$this->repo = new ReceiptRepo('sqlite:///:memory:');
	}

	/**
	 * Create the different test receipts and their expected points.
	 *
	 * @return array
	 */
	public static function cases(): array {
		return [
			'readme-target' => [
				'receipt' => ReceiptExamples::jsonExamples('readme-target'),
				'expected' => 28,
			],
			'readme-mandm' => [
				'receipt' => ReceiptExamples::jsonExamples('readme-mandm'),
				'expected' => 109,
			],
			'morning-receipt' => [
				'receipt' => ReceiptExamples::jsonExamples('morning-receipt'),
				'expected' => 15,
			],
			'simple-receipt' => [
				'receipt' => ReceiptExamples::jsonExamples('simple-receipt'),
				'expected' => 31,
			],
		];
	}

	#[DataProvider('cases')]
	#[TestDox('Endpoints give expected results for receipt $_dataName.')]
	public function testEndpoints(string $receipt, int $expected) {
		$calc = new PointCalculator([
			new ItemCount(),
			new ItemDescriptions(),
			new PurchaseDay(),
			new PurchaseTime(),
			new RetailerName(),
			new TotalAmount(),
		]);
		$processEndpoint = new ProcessReceipt(repo: $this->repo, calc: $calc);

		$incomingProcess = new Request(
			uri: 'http://localhost:8000/receipt/process',
			method: 'POST'
		);
		$incomingProcess->getBody()->write($receipt);

		$outgoingProcess = $processEndpoint->run($incomingProcess, new Response());
		$outgoingProcessBody = json_decode($outgoingProcess->getBody()->__toString(), true);

		$this->assertArrayHasKey('id', $outgoingProcessBody);
		$this->assertInstanceOf(UuidInterface::class, Uuid::fromString($outgoingProcessBody['id']));

		$id = $outgoingProcessBody['id'];

		$pointsEndpoint = new GetPoints(repo: $this->repo);

		$incomingPoints = new Request(
			uri: "http://localhost:8000/receipt/{$id}/points",
			method: 'GET',
		);

		$outgoingPoints = $pointsEndpoint->run($incomingPoints, new Response(), ['id' => $id]);

		$this->assertJsonStringEqualsJsonString(
			"{ \"points\": {$expected} }",
			$outgoingPoints->getBody()->__toString(),
		);
	}

	/**
	 * Delete the database file.
	 *
	 * @return void
	 */
	public static function tearDownAfterClass(): void {
		unlink(__DIR__ . '/testdb.sqlite');
	}
}
