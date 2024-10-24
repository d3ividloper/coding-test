<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Money;

class Product
{
    public function __construct(
        private readonly string $id,
        private string       $description,
        private int          $category,
        private Money        $price
    ){}


    public function getId(): string
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCategory(): int
    {
        return $this->category;
    }

    public function setCategory(int $category): void
    {
        $this->category = $category;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function setPrice(Money $price): void
    {
        $this->price = $price;
    }
}
