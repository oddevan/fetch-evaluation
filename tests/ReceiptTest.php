<?php

namespace oddEvan\FetchEvaluation;

require_once __DIR__ . '/examples/ReceiptExamples.php';

use oddEvan\FetchEvaluation\Test\ReceiptExamples;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

/**
 * Test that Receipts will serialize and deserialize correctly.
 */
final class ReceiptTest extends TestCase {
	/**
	 * Set up the different examples and their expected results.
	 *
	 * @return array
	 */
	public static function cases(): array {
		return [
			'morning-receipt' => [
				'json' => ReceiptExamples::jsonExamples('morning-receipt'),
				'object' => new Receipt(
					retailer: 'Walgreens',
					purchaseDate: '2022-01-02',
					purchaseTime: '08:13',
					total: '2.65',
					items: [
						new ReceiptItem(shortDescription: 'Pepsi - 12-oz', price: '1.25'),
						new ReceiptItem(shortDescription: 'Dasani', price: '1.40'),
					]
				),
			],
			'readme-mandm' => [
				'json' => ReceiptExamples::jsonExamples('readme-mandm'),
				'object' => new Receipt(
					retailer: 'M&M Corner Market',
					purchaseDate: '2022-03-20',
					purchaseTime: '14:33',
					total: '9.00',
					items: [
						new ReceiptItem(shortDescription: 'Gatorade', price: '2.25'),
						new ReceiptItem(shortDescription: 'Gatorade', price: '2.25'),
						new ReceiptItem(shortDescription: 'Gatorade', price: '2.25'),
						new ReceiptItem(shortDescription: 'Gatorade', price: '2.25'),
					]
				),
			],
			'readme-target' => [
				'json' => ReceiptExamples::jsonExamples('readme-target'),
				'object' => new Receipt(
					retailer: 'Target',
					purchaseDate: '2022-01-01',
					purchaseTime: '13:01',
					total: '35.35',
					items: [
						new ReceiptItem(shortDescription: 'Mountain Dew 12PK', price: '6.49'),
						new ReceiptItem(shortDescription: 'Emils Cheese Pizza', price: '12.25'),
						new ReceiptItem(shortDescription: 'Knorr Creamy Chicken', price: '1.26'),
						new ReceiptItem(shortDescription: 'Doritos Nacho Cheese', price: '3.35'),
						new ReceiptItem(shortDescription: '   Klarbrunn 12-PK 12 FL OZ  ', price: '12.00'),
					]
				),
			],
			'simple-receipt' => [
				'json' => ReceiptExamples::jsonExamples('simple-receipt'),
				'object' => new Receipt(
					retailer: 'Target',
					purchaseDate: '2022-01-02',
					purchaseTime: '13:13',
					total: '1.25',
					items: [
						new ReceiptItem(shortDescription: 'Pepsi - 12-oz', price: '1.25'),
					]
				),
			],
		];
	}

	/**
	 * Test receipt (de)serialization.
	 *
	 * @param string  $json   JSON string for the receipt.
	 * @param Receipt $object Expected object for the receipt.
	 * @return void
	 */
	#[DataProvider('cases')]
	#[TestDox('Receipt $_dataName will serialize and deserialize.')]
	public function testReceipt(string $json, Receipt $object) {
		$this->assertJsonStringEqualsJsonString($json, json_encode($object));
		$this->assertEquals($object, Receipt::fromJson($json));
	}
}
