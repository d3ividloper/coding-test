<?php
declare(strict_types=1);

namespace App\Infrastructure\Repository\InMemory;

use App\Domain\Entity\Customer;
use App\Domain\Exception\InvalidArgumentException;
use App\Domain\Exception\ResourceNotFoundException;
use App\Domain\Repository\CustomerRepositoryInterface;
use App\Domain\ValueObject\Money;
use DateMalformedStringException;
use DateTime;
use JsonException;
use Symfony\Component\Config\Definition\Exception\Exception;

class InMemoryCustomerRepository implements CustomerRepositoryInterface
{
    private const CUSTOMERS_FILE_PATH = '/data/customers.json';

    public function __construct(private string $projectDir){}

    /**
     * @throws JsonException|InvalidArgumentException
     */
    public function getCustomers(): array
    {
        $fileData = @file_get_contents($this->projectDir.self::CUSTOMERS_FILE_PATH);
        if(!$fileData) {
            throw new InvalidArgumentException('Path not found, please check the source file.');
        }
        if (json_validate($fileData)) {
            return json_decode($fileData, true, 512, JSON_THROW_ON_ERROR);
        }
        return [];
    }

    /**
     * @throws DateMalformedStringException
     * @throws JsonException|InvalidArgumentException|ResourceNotFoundException
     */
    public function findCustomer(int $customerId): Customer
    {
        $customerEntity = null;
        $customersList = $this->getCustomers();

        foreach ($customersList as $customer) {
            if((int)$customer['id'] === $customerId) {
                $customerEntity = new Customer(
                    id: (int)$customer['id'],
                    name: $customer['name'],
                    revenue: new Money((float)$customer['revenue']),
                    createdAt: new DateTime($customer['since'])
                );
                break;
            }
        }

        if(!$customerEntity) {
            throw new ResourceNotFoundException('Customer with id '.$customerId.' does not exist');
        }

        return $customerEntity;
    }
}
