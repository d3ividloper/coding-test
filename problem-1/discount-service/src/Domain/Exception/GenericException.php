<?php
declare(strict_types=1);

namespace App\Domain\Exception;

final class GenericException extends DomainException
{
    public function __construct($exceptionDescription = "") {
        parent::__construct(DomainException::GENERIC_ERROR, $exceptionDescription);
    }
}
