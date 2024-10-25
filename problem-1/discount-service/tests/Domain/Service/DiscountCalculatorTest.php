<?php
declare(strict_types=1);

namespace App\Tests\Domain\Service;

use App\Domain\Service\DiscountCalculator;
use App\Domain\Entity\Order;
use App\Domain\Exception\InvalidArgumentException;
use App\Domain\Service\DiscountRuleInterface;
use App\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

class DiscountCalculatorTest extends TestCase
{
    public function testCalculateDiscountThrowsExceptionWhenTotalsDoNotMatch(): void
    {
        // Arrange
        $discountRuleMock = $this->createMock(DiscountRuleInterface::class);
        $discountCalculator = new DiscountCalculator([$discountRuleMock]);

        // Mock the Order entity
        $orderMock = $this->createMock(Order::class);

        // Configure getItemsTotalAmount to return 100
        $itemsTotalAmount = new Money(100.00);
        $orderMock->method('getItemsTotalAmount')->willReturn($itemsTotalAmount);

        // Configure getTotalAmount to return 200
        $totalAmountMock = new Money(200.00);
        $orderMock->method('getTotalAmount')->willReturn($totalAmountMock);

        // Assert: Expect InvalidArgumentException to be thrown
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Order total amount and items total amount must be the same value.');

        // Act: Call calculateDiscount to trigger the exception
        $discountCalculator->calculateDiscount($orderMock);
    }
}

