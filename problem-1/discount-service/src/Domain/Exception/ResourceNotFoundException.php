<?php
declare(strict_types=1);

namespace App\Domain\Exception;

final class ResourceNotFoundException extends DomainException
{
    public function __construct($exceptionDescription = "") {
        parent::__construct(DomainException::RESOURCE_NOT_FOUND, $exceptionDescription);
    }
}
