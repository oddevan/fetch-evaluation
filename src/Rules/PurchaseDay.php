<?php

namespace oddEvan\FetchEvaluation\Rules;

use oddEvan\FetchEvaluation\Receipt;
use oddEvan\FetchEvaluation\RewardRule;

/**
 * 6 points if the day in the purchase date is odd.
 */
class PurchaseDay implements RewardRule {
	/**
	 * Calculate the points awarded by this rule.
	 *
	 * @param Receipt $receipt Receipt to calculate points for.
	 * @return integer
	 */
	public function awardPoints(Receipt $receipt): int {
		$day = \intval(\substr($receipt->purchaseDate, -2));

		return (($day % 2) == 1) ? 6 : 0;
	}
}
