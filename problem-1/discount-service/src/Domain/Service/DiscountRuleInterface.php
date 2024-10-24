<?php

namespace App\Domain\Service;

use App\Domain\Entity\Order;
use App\Domain\ValueObject\Discount;

interface DiscountRuleInterface
{
    public function apply(Order $order): ?Discount;
}
