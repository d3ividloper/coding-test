<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\Order;
use App\Domain\ValueObject\Discount;
use App\Domain\ValueObject\Money;

class DiscountCalculator
{
    public function calculateDiscount(Order $order): Discount
    {
        $total = $order->getTotalPrice();
        $discountAmount = new Money(0);

        // TODO: define rules for discounts
    }
}
