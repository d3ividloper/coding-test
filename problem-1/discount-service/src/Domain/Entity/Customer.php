<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Money;
use DateTime;

class Customer
{
    public function __construct(
        private int $id,
        private string $name,
        private Money $revenue,
        private DateTime $createdAt = new DateTime()
    ){}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getRevenue(): Money
    {
        return $this->revenue;
    }

    public function setRevenue(Money $revenue): void
    {
        $this->revenue = $revenue;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}
