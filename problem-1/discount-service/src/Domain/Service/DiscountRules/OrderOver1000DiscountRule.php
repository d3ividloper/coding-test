<?php

namespace App\Domain\Service;

use App\Domain\Entity\Order;
use App\Domain\ValueObject\Discount;

class OrderOver1000DiscountRule implements DiscountRuleInterface
{
    public function apply(Order $order): ?Discount
    {
        $discount = null;
        if ($order->getTotalAmount()->getAmount() + $order->getCustomer()->getRevenue()->getAmount() > 1000) {
            $discount =  new Discount(
                amount: $order->getTotalAmount()->multiply(0.10),
                description: "Customer revenue over 1000€ -> Got 10% discount from order total amount."
            );
        }

        return $discount;
    }
}
