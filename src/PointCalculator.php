<?php

namespace oddEvan\FetchEvaluation;

/**
 * Service to calculate points for a receipt based on the given rules.
 */
class PointCalculator {
	/**
	 * Create the service.
	 *
	 * @param RewardRule[] $rules Array of rules to use.
	 */
	public function __construct(private array $rules) {
	}

	/**
	 * Calculate the points earned by the given Receipt.
	 *
	 * @param Receipt $receipt Recept to calculate.
	 * @return integer
	 */
	public function pointsForReceipt(Receipt $receipt): int {
		return \array_reduce(
			$this->rules,
			fn($carry, $rule) => $carry + $rule->awardPoints($receipt),
			0
		);
	}
}
