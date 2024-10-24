<?php
declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Contracts\DiscountCalculationUseCaseInterface;
use App\Domain\Entity\Order;
use App\Domain\Repository\CustomerRepositoryInterface;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\Service\DiscountCalculator;
use App\Domain\ValueObject\Item;
use App\Domain\ValueObject\Money;

class DiscountCalculationUseCase implements DiscountCalculationUseCaseInterface
{
    public function __construct(
        private DiscountCalculator $discountCalculatorService,
        private ProductRepositoryInterface $productRepository,
        private CustomerRepositoryInterface $customerRepository
    ){}


    public function calculateDiscount(DiscountCalculationRequest $request): array
    {
        $items = [];
        foreach ($request->items as $item) {
            //Ge the Product entity for each item
            $productEntity =  $this->productRepository->findProduct(productId: $item['product-id']);

            $items[] = new Item(
                product: $productEntity,
                quantity: (int)$item['quantity'],
                unitPrice: new Money((float)$item['unit-price']),
                totalPrice: new Money((float)$item['total'])
            );
        }

        //Get the Customer entity
        $customerEntity = $this->customerRepository->findCustomer($request->customerId);

        $order = new Order(
            id: $request->orderId,
            customer: $customerEntity,
            totalAmount: new Money($request->totalAmount),
            items: $items
        );

        //Call the service
        return $this->discountCalculatorService->calculateDiscount($order);
    }
}



