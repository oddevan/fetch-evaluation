<?php // phpcs:ignore PSR1.Files.SideEffects.FoundWithSymbols

namespace oddEvan\FetchEvaluation\Rules;

require_once __DIR__ . '/../examples/ReceiptExamples.php';

use oddEvan\FetchEvaluation\Receipt;
use oddEvan\FetchEvaluation\Test\ReceiptExamples;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

/**
 * 25 points if the total is a multiple of 0.25.
 * Additional 50 points if the total is a round dollar amount with no cents.
 */
final class TotalAmountTest extends TestCase {
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
				'expected' => 75,
			],
			'morning-receipt' => [
				'receipt' => ReceiptExamples::jsonExamples('morning-receipt'),
				'expected' => 0,
			],
			'simple-receipt' => [
				'receipt' => ReceiptExamples::jsonExamples('simple-receipt'),
				'expected' => 25,
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
		$rule = new TotalAmount();

		$this->assertEquals($expected, $rule->awardPoints($receiptObj));
	}
}
