<?php

namespace oddEvan\FetchEvaluation;

/**
 * Value object to hold a Receipt's line item.
 */
readonly class ReceiptItem {
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
}
