<?php

namespace oddEvan\FetchEvaluation\Test;

/**
 * Store example receipt JSON
 */
class ReceiptExamples {
	/**
	 * Cache the strings from the JSON files.
	 *
	 * @var array
	 */
	private static array $jsonExamples;

	/**
	 * Get the JSON for the given receipt
	 *
	 * @param string $key Key for the receipt to fetch.
	 * @return string
	 */
	public static function jsonExamples(string $key): string {
		self::$jsonExamples ??= [
			'readme-target' => file_get_contents(__DIR__ . '/readme-target.json'),
			'readme-mandm' => file_get_contents(__DIR__ . '/readme-mandm.json'),
			'morning-receipt' => file_get_contents(__DIR__ . '/morning-receipt.json'),
			'simple-receipt' => file_get_contents(__DIR__ . '/simple-receipt.json'),
		];

		return self::$jsonExamples[$key];
	}
}
