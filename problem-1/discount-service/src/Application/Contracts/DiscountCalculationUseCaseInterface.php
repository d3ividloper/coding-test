<?php

namespace App\Application\Contracts;

use App\Application\UseCase\DiscountCalculationRequest;

interface DiscountCalculationUseCaseInterface
{
    public function calculateDiscount(DiscountCalculationRequest $request): array;
}
