<?php
declare(strict_types=1);

namespace App\Domain\Exception;

use Exception;

class DomainException extends Exception
{
    public const GENERIC_ERROR = 'E00001';
    public const BAD_REQUEST = 'E00002';
    public const INVALID_ARGUMENT = 'E00003';
    public const MISSING_ARGUMENT = 'E00004';
    public const RESOURCE_NOT_FOUND = 'E00005';
    public const DUPLICATE_RESOURCE = 'E00006';
    public const CUSTOMER_NOT_FOUND = 'E00007';
    public const NOT_AUTHORIZED = 'E00008';

    public array $descriptionMapping = [
        self::GENERIC_ERROR => 'Generic Request Error',
        self::BAD_REQUEST => 'Bad Request. Invalid Data',
        self::INVALID_ARGUMENT => 'Invalid Argument',
        self::MISSING_ARGUMENT => 'Missing Argument',
        self::RESOURCE_NOT_FOUND => 'Resource Not Found',
        self::DUPLICATE_RESOURCE => 'Duplicate Resource',
        self::CUSTOMER_NOT_FOUND => 'Customer Not Found',
        self::NOT_AUTHORIZED => 'Not Authorized Action'
    ];

    protected string $domainCode;
    private string $description;


    public function __construct(string $code, string $exceptionDescription = null)
    {
        if(!array_key_exists($code, $this->descriptionMapping)){
            $code = self::GENERIC_ERROR;
        }
        $this->domainCode = $code;

        if ($exceptionDescription !== "") {
            $this->descriptionMapping[$code] .= " - " . $exceptionDescription;
        }

        parent::__construct($this->descriptionMapping[$code]);
    }

    public function getDomainCode(): string
    {
        return $this->domainCode;
    }

    public function getDescription(): string
    {
        return $this->descriptionMapping[$this->domainCode];
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
