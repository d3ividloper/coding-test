<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Product;

interface ProductRepositoryInterface
{
    public function getProducts(): array;
    public function findProduct(string $productId): Product;
}
