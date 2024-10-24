<?php
declare(strict_types=1);

namespace App\Domain\Exception;

final class InvalidArgumentException extends DomainException
{
    public function __construct($exceptionDescription = "") {
        parent::__construct(DomainException::INVALID_ARGUMENT, $exceptionDescription);
    }
}
