<?php

namespace App\Tests\Domain\Entity;

use App\Domain\Entity\Customer;
use App\Domain\Entity\Order;
use App\Domain\Entity\Product;
use App\Domain\ValueObject\Item;
use App\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    public function testOrderInitialization(): void
    {
        $customer = new Customer(1, 'Gandalf The grey', new Money(500.00));
        $totalAmount = new Money(100);

        $product1 = new Product('A001', 'Product 1', 1, new Money(1.50) );
        $product2 = new Product('A002', 'Product 2', 2, new Money(2.00) );
        $items = [
            new Item($product1, 2, new Money(1.50), new Money(3.00)),
            new Item($product2, 2, new Money(2.00), new Money(4.00))
        ];

        // Act
        $order = new Order(1, $customer, $totalAmount, $items);

        // Assert
        $this->assertEquals(1, $order->getId());
        $this->assertSame($customer, $order->getCustomer());
        $this->assertSame($totalAmount, $order->getTotalAmount());
        $this->assertEquals($items, $order->getItems());
    }

    public function testGetItemsTotalAmountWithItems(): void
    {
        $item1 = $this->createMock(Item::class);
        $item1->method('getUnitPrice')->willReturn(new Money(10));
        $item1->method('getQuantity')->willReturn(2);

        $item2 = $this->createMock(Item::class);
        $item2->method('getUnitPrice')->willReturn(new Money(15));
        $item2->method('getQuantity')->willReturn(1);

        $items = [$item1, $item2];
        $order = new Order(1, $this->createMock(Customer::class), new Money(35), $items);

        $totalItemsAmount = $order->getItemsTotalAmount();

        $this->assertEquals(35, $totalItemsAmount->getAmount());
    }

    public function testGetItemsTotalAmountWithNoItems(): void
    {
        $order = new Order(1, $this->createMock(Customer::class), new Money(0), []);
        $totalItemsAmount = $order->getItemsTotalAmount();
        $this->assertEquals(0, $totalItemsAmount->getAmount());
    }

    public function testAddItem(): void
    {
        $order = new Order(1, $this->createMock(Customer::class), new Money(0), []);
        $item = $this->createMock(Item::class);
        $order->addItem($item);
        $this->assertCount(1, $order->getItems());
        $this->assertSame($item, $order->getItems()[0]);
    }
}
