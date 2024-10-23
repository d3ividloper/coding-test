<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;

class Discount
{
    public function __construct(
        private Money $amount
    ){}

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function applyTo(Money $total): Money
    {
        return $total->subtract($this->amount);
    }
}
