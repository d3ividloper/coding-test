<?php

namespace App\Domain\Service;

use App\Domain\Entity\Order;
use App\Domain\Service\DiscountRuleInterface;
use App\Domain\ValueObject\Discount;

class SwitchesCategoryDiscountRule implements DiscountRuleInterface
{
    private const SWITCHES_CATEGORY_ID = 2;
    private const MIN_QUANTITY_FOR_FREE = 5;

    //If a customer buy 5 Product of Category "Switches" (id=2) -> get 6th for free.
    public function apply(Order $order): ?Discount
    {
        $discount = null;
        $switchesCounter = 0;

        foreach ($order->getItems() as $item) {
            if ($item->getProduct()->getCategory() === self::SWITCHES_CATEGORY_ID) {
                $switchesCounter += $item->getQuantity();
            }
        }

        if ($switchesCounter >= self::MIN_QUANTITY_FOR_FREE) {
            $freeItems = [
                'category' => "Switch",
                'freeQuantity' => intdiv($switchesCounter, self::MIN_QUANTITY_FOR_FREE)
            ];
            $discount = new Discount(
                description: "Bought 5 Switches -> Got 6th switch for free.",
                freeItems: $freeItems
            );
        }

        return $discount;
    }
}
