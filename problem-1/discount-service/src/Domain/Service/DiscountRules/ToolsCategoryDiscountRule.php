<?php

namespace App\Domain\Service;

use App\Domain\Entity\Order;
use App\Domain\Service\DiscountRuleInterface;
use App\Domain\ValueObject\Discount;

class ToolsCategoryDiscountRule implements DiscountRuleInterface
{
    private const TOOLS_CATEGORY_ID = 1;
    private const MIN_QUANTITY_FOR_DISCOUNT = 2;
    private const DISCOUNT_AMOUNT = 0.20;

    // If a customer buy 2+ Products of category "Tools" (id=1) -> get a 20% discount on the cheapest Product.
    public function apply(Order $order): ?Discount
    {
        $toolsCounter = 0;
        $cheapestItemPrice = 0;
        $discount = null;

        foreach ($order->getItems() as $item) {
            if ($item->getProduct()->getCategory() === self::TOOLS_CATEGORY_ID) {
                $toolsCounter += $item->getQuantity();
                if ($cheapestItemPrice === 0) {
                    $cheapestItemPrice = $item->getUnitPrice();
                }
                if ($item->getUnitPrice()->getAmount() < $cheapestItemPrice->getAmount()) {
                    $cheapestItemPrice = $item->getUnitPrice();
                }
            }
        }

        if ($toolsCounter >= self::MIN_QUANTITY_FOR_DISCOUNT) {
            $toolsDiscount = $cheapestItemPrice->multiply(self::DISCOUNT_AMOUNT);
            $discount = new Discount(
                amount: $toolsDiscount,
                description: "Bought 2 or more Tools -> Got 20% discount over cheapest item."
            );
        }

        return $discount;
    }
}
