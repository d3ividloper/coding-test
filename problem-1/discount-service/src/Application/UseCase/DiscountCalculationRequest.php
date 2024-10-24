<?php
declare(strict_types=1);

namespace App\Application\UseCase;

use App\Domain\ValueObject\Money;

final class DiscountCalculationRequest
{
    public function __construct(
        public int $orderId,
        public int $customerId,
        public array $items,
        public float $totalAmount
    ){}
}
