<?php

namespace oddEvan\FetchEvaluation;

use JsonSerializable;

/**
 * Value object to represent a Receipt provided to the system.
 */
readonly class Receipt implements JsonSerializable {
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
	 * Specify data which should be serialized to JSON
	 *
	 * @return array
	 */
	public function jsonSerialize(): array {
		$base = \get_object_vars($this);
		$base['items'] = array_map(fn($li) => $li->jsonSerialize(), $this->items);
		return $base;
	}
}
