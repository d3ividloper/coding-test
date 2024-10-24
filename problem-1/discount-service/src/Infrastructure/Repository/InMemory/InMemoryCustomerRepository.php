<?php

namespace App\Infrastructure\Repository\InMemory;

use App\Domain\Entity\Customer;
use App\Domain\Repository\CustomerRepositoryInterface;

class InMemoryCustomerRepository implements CustomerRepositoryInterface
{

    public function getCustomers(): array
    {
        // TODO: Implement getCustomers() method.
    }

    public function findCustomer(int $customerId, array $customersList): Customer
    {
        // TODO: Implement findCustomer() method.
    }
}
