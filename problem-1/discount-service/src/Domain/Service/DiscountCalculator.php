<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Order;
use App\Domain\Exception\InvalidArgumentException;

class DiscountCalculator
{
    /** @var iterable<DiscountRuleInterface> */
    private iterable $discountRules;

    public function __construct(iterable $discountRules)
    {
        $this->discountRules = $discountRules;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function calculateDiscount(Order $order): array
    {
        if($order->getItemsTotalAmount()->getAmount() !== $order->getTotalAmount()->getAmount()){
            throw new InvalidArgumentException('Order total amount and items total amount must be the same value.');
        }
        $discountsArray = [];
        foreach ($this->discountRules as $rule) {
            if($rule->apply($order) !== null) {
                $discountsArray[] = $rule->apply($order);
            }
        }
        return $discountsArray;
    }
}
