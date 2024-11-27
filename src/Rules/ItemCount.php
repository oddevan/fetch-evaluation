<?php

namespace oddEvan\FetchEvaluation\Rules;

use oddEvan\FetchEvaluation\Receipt;
use oddEvan\FetchEvaluation\RewardRule;

/**
 * 5 points for every two items on the receipt.
 */
class ItemCount implements RewardRule {
	/**
	 * Calculate the points awarded by this rule.
	 *
	 * @param Receipt $receipt Receipt to calculate points for.
	 * @return integer
	 */
	public function awardPoints(Receipt $receipt): int {
		return intdiv(count($receipt->items), 2) * 5;
	}
}
