<?php
declare(strict_types=1);

namespace App\Tests\Application\UseCase;

use App\Application\UseCase\DiscountCalculationRequest;
use App\Application\UseCase\DiscountCalculationUseCase;
use App\Domain\Entity\Customer;
use App\Domain\Entity\Order;
use App\Domain\Entity\Product;
use App\Domain\Repository\CustomerRepositoryInterface;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\Service\DiscountCalculator;
use App\Domain\ValueObject\Discount;
use App\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

class DiscountCalculationUseCaseTest extends TestCase
{
    private $discountCalculatorService;
    private $productRepository;
    private $customerRepository;
    private $useCase;

    protected function setUp(): void
    {
        // Mock dependencies
        $this->discountCalculatorService = $this->createMock(DiscountCalculator::class);
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->customerRepository = $this->createMock(CustomerRepositoryInterface::class);

        // Instantiate the use case with the mocked dependencies
        $this->useCase = new DiscountCalculationUseCase(
            $this->discountCalculatorService,
            $this->productRepository,
            $this->customerRepository
        );
    }

    public function testCalculateDiscountSuccess(): void
    {
        // Arrange
        $requestData = [
            'id' => '1',
            'customer-id' => '1',
            'total' => 100.0,
            'items' => [
                [
                    'product-id' => 'product-1',
                    'quantity' => 1,
                    'unit-price' => 50.0,
                    'total' => 50.0,
                ],
                [
                    'product-id' => 'product-2',
                    'quantity' => 2,
                    'unit-price' => 25.0,
                    'total' => 50.0,
                ],
            ],
        ];
        $request = $this->createDiscountCalculationRequest($requestData);

        // Mock product repository behavior
        $this->productRepository->expects($this->exactly(2))
            ->method('findProduct')
            ->willReturnCallback(function ($productId) {
                if ($productId === 'product-1') {
                    return $this->createProductEntity('product-1','Product Test 1', 1, 9.50);
                } elseif ($productId === 'product-2') {
                    return $this->createProductEntity('product-2','Product Test 2', 2, 5.00);
                }
                return null; // In case of an unexpected product ID
            });

        // Mock customer repository behavior
        $this->customerRepository->expects($this->once())
            ->method('findCustomer')
            ->with($this->equalTo(1))
            ->willReturn($this->createCustomerEntity(1, 'Dark Vader', 950.00));

        // Mock discount calculator behavior
        $discount = $this->createDiscountVO(new Money(9.5), "Customer revenue over 1000€ -> Got 10% discount from order total amount.", []);
        $expectedDiscountResponse = [$discount];

        $this->discountCalculatorService->expects($this->once())
            ->method('calculateDiscount')
            ->with($this->isInstanceOf(Order::class))
            ->willReturn($expectedDiscountResponse);

        // Act
        $result = $this->useCase->calculateDiscount($request);

        // Assert
        $this->assertEquals($expectedDiscountResponse, $result);
    }

    public function testCalculateDiscountWithEmptyItems(): void
    {
        // Arrange
        $requestData = [
            'id' => '2',
            'customer-id' => '2',
            'total' => 0.00,
            'items' => [],
        ];
        $request = $this->createDiscountCalculationRequestWithEmptyItems($requestData);

        // Mock customer repository behavior
        $this->customerRepository->expects($this->once())
            ->method('findCustomer')
            ->with($this->equalTo(2))
            ->willReturn($this->createCustomerEntity(2, 'R2D2', 500.00));

        // Expect no discount calculation due to empty items
        $this->discountCalculatorService->expects($this->once())
            ->method('calculateDiscount')
            ->with($this->isInstanceOf(Order::class))
            ->willReturn([]);

        // Act
        $result = $this->useCase->calculateDiscount($request);

        // Assert
        $this->assertEquals([], $result);
    }


    // Helper methods to mock request and entities

    private function createDiscountCalculationRequest(array $requestData): DiscountCalculationRequest
    {
        return new DiscountCalculationRequest(
            orderId: (int)$requestData['id'],
            customerId: (int)$requestData['customer-id'],
            items: $requestData['items'],
            totalAmount:  (float)$requestData['total']
        );
    }

    private function createDiscountCalculationRequestWithEmptyItems($requestData): DiscountCalculationRequest
    {
        return new DiscountCalculationRequest(
            orderId: (int)$requestData['id'],
            customerId: (int)$requestData['customer-id'],
            items: $requestData['items'],
            totalAmount:  (float)$requestData['total']
        );
    }

    private function createProductEntity(string $productId, string $description, int $category, float $price): Product
    {
        return new Product(
            id: $productId,
            description: $description,
            category: $category,
            price: new Money($price)
        );
    }

    private function createCustomerEntity(int $customerId, string $name, float $revenue): Customer
    {
        return new Customer(id: $customerId,
            name: $name,
            revenue: new Money($revenue),
            createdAt: new \DateTime()
        );
    }

    private function createDiscountVO(Money $amount, string $description, array $freeItems): Discount
    {
        return new Discount(amount: $amount, description: $description, freeItems: $freeItems);
    }
}
