<?php
declare(strict_types=1);

namespace App\Infrastructure\Repository\InMemory;

use App\Domain\Entity\Product;
use App\Domain\Exception\InvalidArgumentException;
use App\Domain\Exception\ResourceNotFoundException;
use App\Domain\Repository\ProductRepositoryInterface;
use App\Domain\ValueObject\Money;
use JsonException;

class InMemoryProductRepository implements ProductRepositoryInterface
{
    private const PRODUCTS_FILE_PATH = '/data/products.json';

    public function __construct(private string $projectDir){}

    /**
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function getProducts(): array
    {
        $fileData = @file_get_contents($this->projectDir.self::PRODUCTS_FILE_PATH);
        if(!$fileData) {
            throw new InvalidArgumentException('Path not found, please check the source file.');
        }
        if (json_validate($fileData)) {
            return json_decode($fileData, true, 512, JSON_THROW_ON_ERROR);
        }
        return [];
    }

    /**
     * @throws JsonException
     * @throws InvalidArgumentException|ResourceNotFoundException
     */
    public function findProduct(string $productId): Product
    {
        $productEntity = null;
        $productsList = $this->getProducts();

        foreach ($productsList as $product) {
            if($product['id'] === $productId) {
                $productEntity = new Product(
                    id: $product['id'],
                    description: $product['description'],
                    category: (int)$product['category'],
                    price: new Money((float)$product['price'])
                );
                break;
            }
        }
        if(!$productEntity) {
            throw new ResourceNotFoundException('Product with id '.$productId.' does not exist');
        }
        return $productEntity;
    }
}
