<?php // phpcs:ignore PSR1.Files.SideEffects.FoundWithSymbols

namespace oddEvan\FetchEvaluation\Rules;

require_once __DIR__ . '/../examples/ReceiptExamples.php';

use oddEvan\FetchEvaluation\Receipt;
use oddEvan\FetchEvaluation\Test\ReceiptExamples;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

/**
 * If the trimmed length of the item description is a multiple of 3, multiply the price by 0.2 and round up to the
 * nearest integer. The result is the number of points earned.
 */
final class ItemDescriptionsTest extends TestCase {
	/**
	 * Create the different test receipts and their expected points.
	 *
	 * @return array
	 */
	public static function cases(): array {
		return [
			'readme-target' => [
				'receipt' => ReceiptExamples::jsonExamples('readme-target'),
				'expected' => 6,
			],
			'readme-mandm' => [
				'receipt' => ReceiptExamples::jsonExamples('readme-mandm'),
				'expected' => 0,
			],
			'morning-receipt' => [
				'receipt' => ReceiptExamples::jsonExamples('morning-receipt'),
				'expected' => 1,
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
		$rule = new ItemDescriptions();

		$this->assertEquals($expected, $rule->awardPoints($receiptObj));
	}
}
