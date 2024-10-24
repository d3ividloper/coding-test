<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Entity\Product;

class Item
{
    public function __construct(
        private readonly Product     $product,
        private readonly int     $quantity,
        private readonly Money   $unitPrice,
        private readonly Money   $totalPrice
    ){}


    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getUnitPrice(): Money
    {
        return $this->unitPrice;
    }

    public function getTotalPrice(): Money
    {
        return $this->totalPrice;
    }

    public function toArray(): array
    {
        return [
            'productId' => $this->getProduct()->getId(),
            'productDescription' => $this->getProduct()->getDescription(),
            'quantity' => $this->getQuantity(),
            'unitPrice' => $this->getUnitPrice()->getAmount(),
            'total' => $this->getTotalPrice()->getAmount()
        ];
    }

}
