<?php

namespace App\Infrastructure\Repository\InMemory;

use App\Domain\Entity\Product;
use App\Domain\Repository\ProductRepositoryInterface;

class InMemoryProductRepository implements ProductRepositoryInterface
{

    public function findProduct(string $productId, array $productsList): Product
    {
        // TODO: Implement findProduct() method.
    }
}
