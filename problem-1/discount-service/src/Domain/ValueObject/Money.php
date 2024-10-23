<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;

class Money
{

    public function __construct(private float $amount)
    {
    }


    public function getAmount(): float
    {
        return $this->amount;
    }

    public function add(Money $added): Money
    {
        return new self($this->amount + $added->amount);
    }

    public function subtract(Money $subtracted): Money
    {
        return new self($this->amount - $subtracted->amount);
    }

    public function product(float $factor): Money
    {
        return new self($this->amount * $factor);
    }

}
