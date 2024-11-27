<?php // phpcs:ignore PSR1.Files.SideEffects.FoundWithSymbols

namespace oddEvan\FetchEvaluation\Rules;

require_once __DIR__ . '/../examples/ReceiptExamples.php';

use oddEvan\FetchEvaluation\Receipt;
use oddEvan\FetchEvaluation\Test\ReceiptExamples;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

/**
 * One point for every alphanumeric character in the retailer name.
 */
final class RetailerNameTest extends TestCase {
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
				'expected' => 14,
			],
			'morning-receipt' => [
				'receipt' => ReceiptExamples::jsonExamples('morning-receipt'),
				'expected' => 9,
			],
			'simple-receipt' => [
				'receipt' => ReceiptExamples::jsonExamples('simple-receipt'),
				'expected' => 6,
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
		$rule = new RetailerName();

		$this->assertEquals($expected, $rule->awardPoints($receiptObj));
	}

	/**
	 * Test that the replace function being used is actually working.
	 *
	 * @return void
	 */
	public function testRegularExpressionDoesWhatYouThinkItDoes() {
		$actual = preg_replace(
			pattern: '/[^\w\d]/i',
			replacement: '',
			subject: 'M&M Corner Market',
		);

		$this->assertEquals('MMCornerMarket', $actual);
	}
}
