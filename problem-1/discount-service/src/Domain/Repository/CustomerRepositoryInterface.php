<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Customer;

interface CustomerRepositoryInterface
{
    public function getCustomers(): array;
    public function findCustomer(int $customerId): Customer;
}
