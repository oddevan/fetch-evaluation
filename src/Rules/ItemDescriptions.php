<?php

namespace oddEvan\FetchEvaluation\Rules;

use oddEvan\FetchEvaluation\Receipt;
use oddEvan\FetchEvaluation\ReceiptItem;
use oddEvan\FetchEvaluation\RewardRule;

/**
 * If the trimmed length of the item description is a multiple of 3, multiply the price by 0.2 and round up to the
 * nearest integer. The result is the number of points earned.
 */
class ItemDescriptions implements RewardRule {
	/**
	 * Calculate the points awarded by this rule.
	 *
	 * @param Receipt $receipt Receipt to calculate points for.
	 * @return integer
	 */
	public function awardPoints(Receipt $receipt): int {
		return array_reduce(
			$receipt->items,
			fn($carry, $item) => $carry + $this->pointsForLine($item),
			0,
		);
	}

	/**
	 * Get the points awarded by the given line item
	 *
	 * @param ReceiptItem $line Line item to calculate points for.
	 * @return integer
	 */
	private function pointsForLine(ReceiptItem $line): int {
		$trimmed = trim($line->shortDescription);
		if ((strlen($trimmed) % 3) !== 0) {
			return 0;
		}

		$multiplied = bcmul($line->price, '0.20', 2);
		$rounded = bcceil($multiplied);
		return intval($rounded);
	}
}
