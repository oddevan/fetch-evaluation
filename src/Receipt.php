<?php

namespace oddEvan\FetchEvaluation;

use DateTimeImmutable;
use Exception;

/**
 * Value object to represent a Receipt provided to the system.
 */
readonly class Receipt {
	/**
	 * Store the line items for this receipt.
	 *
	 * @var ReceiptItem[]
	 */
	public array $items;

	/**
	 * Construct the object.
	 *
	 * @param string  $retailer     Name of the retailer this receipt is for.
	 * @param string  $purchaseDate Date of the purchase in YYYY-MM-DD format.
	 * @param string  $purchaseTime Time of the purchase in 24-hour HH:MM format.
	 * @param string  $total        Total amount paid for the purchase.
	 * @param array[] $items        Line items on the receipt in an array of assoiative arrays.
	 */
	public function __construct(
		public string $retailer,
		public string $purchaseDate,
		public string $purchaseTime,
		public string $total,
		array $items,
	) {
		$this->items = array_map(fn($li) => new ReceiptItem(...$li), $items);
	}

	/**
	 * Create a new Receipt from a JSON string.
	 *
	 * @param string $json Receipt JSON.
	 * @return self
	 */
	public static function fromJson(string $json): self {
		return new self(...json_decode($json, associative: true));
	}

	/**
	 * Get the purchase date and time as a DateTimeImmutable object.
	 *
	 * @throws Exception When date or time is not formatted correctly.
	 *
	 * @return DateTimeImmutable
	 */
	public function datetime(): DateTimeImmutable {
		$obj = DateTimeImmutable::createFromFormat('Y-m-d H:i', $this->purchaseDate . ' ' . $this->purchaseTime);
		if ($obj === false) {
			throw new Exception(
				"Purchase date ({$this->purchaseDate}) and/or time ({$this->purchaseTime}) is not in expected format."
			);
		}

		return $obj;
	}
}
