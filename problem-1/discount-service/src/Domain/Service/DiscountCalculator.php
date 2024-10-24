<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Order;

class DiscountCalculator
{
    /** @var iterable<DiscountRuleInterface> */
    private iterable $discountRules;

    public function __construct(iterable $discountRules)
    {
        $this->discountRules = $discountRules;
    }

    public function calculateDiscount(Order $order): array
    {
        $discountsArray = [];
        foreach ($this->discountRules as $rule) {
            if($rule->apply($order) !== null) {
                $discountsArray[] = $rule->apply($order);
            }
        }
        return $discountsArray;
    }
}
