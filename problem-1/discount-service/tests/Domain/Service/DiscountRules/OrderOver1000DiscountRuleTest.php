<?php

namespace App\Tests\Domain\Service\DiscountRules;

use App\Domain\Entity\Order;
use App\Domain\Service\DiscountRules\OrderOver1000DiscountRule;
use App\Domain\ValueObject\Discount;
use App\Domain\ValueObject\Money;
use App\Domain\Entity\Customer;
use PHPUnit\Framework\TestCase;

class OrderOver1000DiscountRuleTest extends TestCase
{
    public function testApplyDiscountWhenOrderAndRevenueExceed1000(): void
    {
        // Arrange
        $orderTotalAmount = $this->createConfiguredMock(Money::class, ['getAmount' => 800.00]);
        $customerRevenue = $this->createConfiguredMock(Money::class, ['getAmount' => 300.00]);

        $customerMock = $this->createMock(Customer::class);
        $customerMock->method('getRevenue')->willReturn($customerRevenue);

        $orderMock = $this->createMock(Order::class);
        $orderMock->method('getTotalAmount')->willReturn($orderTotalAmount);
        $orderMock->method('getCustomer')->willReturn($customerMock);

        $orderTotalAmount->method('multiply')->with(0.10)->willReturn(new Money(80));

        $rule = new OrderOver1000DiscountRule();
        $discount = $rule->apply($orderMock);

        // Assert
        $this->assertInstanceOf(Discount::class, $discount);
        $this->assertEquals(80, $discount->getAmount()->getAmount());
        $this->assertEquals("Customer revenue over 1000€ -> Got 10% discount from order total amount.", $discount->getDescription());
    }

    public function testNoDiscountWhenOrderAndRevenueDoNotExceed1000(): void
    {
        $orderTotalAmount = $this->createConfiguredMock(Money::class, ['getAmount' => 500.00]);
        $customerRevenue = $this->createConfiguredMock(Money::class, ['getAmount' => 400.00]);

        $customerMock = $this->createMock(Customer::class);
        $customerMock->method('getRevenue')->willReturn($customerRevenue);

        $orderMock = $this->createMock(Order::class);
        $orderMock->method('getTotalAmount')->willReturn($orderTotalAmount);
        $orderMock->method('getCustomer')->willReturn($customerMock);

        $rule = new OrderOver1000DiscountRule();
        $discount = $rule->apply($orderMock);

        $this->assertNull($discount);
    }

    public function testNoDiscountWhenOrderTotalAmountIsZero(): void
    {
        $orderTotalAmount = $this->createConfiguredMock(Money::class, ['getAmount' => 0.00]);
        $customerRevenue = $this->createConfiguredMock(Money::class, ['getAmount' => 1500.00]);

        $customerMock = $this->createMock(Customer::class);
        $customerMock->method('getRevenue')->willReturn($customerRevenue);

        $orderMock = $this->createMock(Order::class);
        $orderMock->method('getTotalAmount')->willReturn($orderTotalAmount);
        $orderMock->method('getCustomer')->willReturn($customerMock);

        $rule = new OrderOver1000DiscountRule();
        $discount = $rule->apply($orderMock);

        $this->assertNull($discount);
    }
}
