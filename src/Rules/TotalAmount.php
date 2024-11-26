<?php

namespace oddEvan\FetchEvaluation\Rules;

use oddEvan\FetchEvaluation\Receipt;
use oddEvan\FetchEvaluation\RewardRule;

/**
 * 25 points if the total is a multiple of 0.25.
 * Additional 50 points if the total is a round dollar amount with no cents.
 */
class TotalAmount implements RewardRule {
	/**
	 * Calculate the points awarded by this rule.
	 *
	 * @param Receipt $receipt Receipt to calculate points for.
	 * @return integer
	 */
	public function awardPoints(Receipt $receipt): int {
		// Check for divisibility by 0.25.
		if (bcmod($receipt->total, '0.25', 2) != '0.00') {
			return 0;
		}

		// Check for divisibility by 1.00.
		if (bcmod($receipt->total, '1.00', 2) != '0.00') {
			return 25;
		}

		return 75;
	}
}
