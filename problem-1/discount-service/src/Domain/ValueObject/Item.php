<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;

class Item
{
    public function __construct(
        private Product $product,
        private Money $price,
        private int $quantity
    ){}

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getTotalPrice(): Money
    {
        return $this->price->product($this->quantity);
    }

}
