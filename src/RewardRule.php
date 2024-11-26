<?php

namespace oddEvan\FetchEvaluation;

/**
 * Defines a service that calculates points for a Receipt.
 */
interface RewardRule {
	/**
	 * Calculate the points awarded by this rule.
	 *
	 * @param Receipt $receipt Receipt to calculate points for.
	 * @return integer
	 */
	public function awardPoints(Receipt $receipt): int;
}
