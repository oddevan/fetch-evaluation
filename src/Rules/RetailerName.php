<?php

namespace oddEvan\FetchEvaluation\Rules;

use Exception;
use oddEvan\FetchEvaluation\Receipt;
use oddEvan\FetchEvaluation\RewardRule;

/**
 * One point for every alphanumeric character in the retailer name.
 */
class RetailerName implements RewardRule {
	/**
	 * Calculate the points awarded by this rule.
	 *
	 * @throws Exception When parsing the retailer name fails.
	 *
	 * @param Receipt $receipt Receipt to calculate points for.
	 * @return integer
	 */
	public function awardPoints(Receipt $receipt): int {
		$alphanumericOnly = \preg_replace(
			pattern: '[^\w\d]',
			replacement: '',
			subject: $receipt->retailer
		);
		if (!\is_string($alphanumericOnly)) {
			throw new Exception('Could not parse retailer name: ' . $receipt->retailer);
		}

		return strlen($alphanumericOnly);
	}
}
