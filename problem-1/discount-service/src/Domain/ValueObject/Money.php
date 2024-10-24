<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;

class Money
{

    public function __construct(private float $amount, private string $currency = 'EURO')
    {
    }


    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getAmountWithCurrency(): string
    {
        return $this->amount. ' '.$this->currency;
    }

    public function add(Money $added): Money
    {
        return new self($this->amount + $added->amount);
    }

    public function subtract(Money $subtracted): Money
    {
        return new self($this->amount - $subtracted->amount);
    }

    public function multiply(float $factor): Money
    {
        return new self(round($this->amount * $factor, 2));
    }

}
