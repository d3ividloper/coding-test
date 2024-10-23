<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Item;
use App\Domain\ValueObject\Money;

class Order
{
    public function __construct(
        private int $id,
        private Customer $customer,
        private array $items = []
    ){}


    public function getId(): int
    {
        return $this->id;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function addItem(Item $item): void
    {
        $this->items[] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotalPrice(): Money
    {
        $total = new Money(0);

        foreach ($this->items as $item) {
            $total = $total->add($item->getTotalPrice());
        }

        return $total;
    }
}
