<?php

namespace oddEvan\FetchEvaluation\Rules;

use oddEvan\FetchEvaluation\Receipt;
use oddEvan\FetchEvaluation\RewardRule;

/**
 * 10 points if the time of purchase is after 2:00pm and before 4:00pm.
 *
 * Working on the assumption that the range is inclusive (i.e. 2:00pm and 4:00pm exactly will both be worth 10 points.)
 */
class PurchaseTime implements RewardRule {
	/**
	 * Calculate the points awarded by this rule.
	 *
	 * @param Receipt $receipt Receipt to calculate points for.
	 * @return integer
	 */
	public function awardPoints(Receipt $receipt): int {
		$hour = substr($receipt->purchaseTime, 0, 2);
		if ($hour == '14' || $hour == '15' || $receipt->purchaseTime == '16:00') {
			return 10;
		}

		return 0;
	}
}
