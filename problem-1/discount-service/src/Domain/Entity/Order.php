<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Item;
use App\Domain\ValueObject\Money;

class Order
{
    private int $id;
    private Customer $customer;
    private array $items;
    private Money $totalAmount;

    public function __construct(
        int $id,
        Customer $customer,
        Money $totalAmount,
        array $items = []
    ){
        $this->id = $id;
        $this->customer = $customer;
        $this->totalAmount = $totalAmount;
        $this->items = [];
        foreach ($items as $item) {
            $this->addItem($item);
        }
    }


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

    public function getTotalAmount(): Money
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(Money $totalAmount): void
    {
        $this->totalAmount = $totalAmount;
    }

    public function addItem(Item $item): void
    {
        $this->items[] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getItemsTotalAmount(): Money
    {
        $amount = new Money(0);
        if(count($this->items) === 0) {
            return $amount;
        }

        foreach($this->items as $item) {
            $amount = $amount->add($item->getUnitPrice()->multiply($item->getQuantity()));
        }

        return $amount;
    }
}
