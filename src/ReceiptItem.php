<?php

namespace oddEvan\FetchEvaluation;

use JsonSerializable;

/**
 * Value object to hold a Receipt's line item.
 */
readonly class ReceiptItem implements JsonSerializable {
	/**
	 * Construct the object.
	 *
	 * @param string $shortDescription Description of the item.
	 * @param string $price            Price paid for the item.
	 */
	public function __construct(
		public string $shortDescription,
		public string $price,
	) {
	}

	/**
	 * Specify data which should be serialized to JSON
	 *
	 * @return array
	 */
	public function jsonSerialize(): array {
		return get_object_vars($this);
	}
}
