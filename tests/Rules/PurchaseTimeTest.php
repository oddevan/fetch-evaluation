<?php // phpcs:ignore PSR1.Files.SideEffects.FoundWithSymbols

namespace oddEvan\FetchEvaluation\Rules;

require_once __DIR__ . '/../examples/ReceiptExamples.php';

use oddEvan\FetchEvaluation\Receipt;
use oddEvan\FetchEvaluation\Test\ReceiptExamples;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

/**
 * 10 points if the time of purchase is after 2:00pm and before 4:00pm.
 *
 * Working on the assumption that the range is inclusive (i.e. 2:00pm and 4:00pm exactly will both be worth 10 points.)
 */
final class PurchaseTimeTest extends TestCase {
	/**
	 * Create the different test receipts and their expected points.
	 *
	 * @return array
	 */
	public static function cases(): array {
		return [
			'readme-target' => [
				'receipt' => ReceiptExamples::jsonExamples('readme-target'),
				'expected' => 0,
			],
			'readme-mandm' => [
				'receipt' => ReceiptExamples::jsonExamples('readme-mandm'),
				'expected' => 10,
			],
			'morning-receipt' => [
				'receipt' => ReceiptExamples::jsonExamples('morning-receipt'),
				'expected' => 0,
			],
			'simple-receipt' => [
				'receipt' => ReceiptExamples::jsonExamples('simple-receipt'),
				'expected' => 0,
			],
		];
	}

	/**
	 * Test the rule against a receipt.
	 *
	 * @param string  $receipt  Receipt to test.
	 * @param integer $expected Expected points given.
	 * @return void
	 */
	#[DataProvider('cases')]
	#[TestDox('Expected results for receipt $_dataName.')]
	public function testPoints(string $receipt, int $expected) {
		$receiptObj = Receipt::fromJson($receipt);
		$rule = new PurchaseTime();

		$this->assertEquals($expected, $rule->awardPoints($receiptObj));
	}
}
