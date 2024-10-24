<?php
declare(strict_types=1);

namespace App\Domain\ValueObject;

class Discount
{
    public function __construct(
        private ?Money $amount = null,
        private ?string $description = null,
        private array $freeItems = []
    ){}

    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function getAmount(): ?Money
    {
        return $this->amount;
    }
    public function getFreeItems(): array
    {
        return $this->freeItems;
    }

    public function toArray(): array
    {
        $items = [];
        if($this->description) {
            $items['discountDescription'] = $this->description;
        }

        if ($this->amount){
            $items['discountAmount'] = round($this->amount->getAmount(), 2);
        }
        foreach($this->freeItems as $item){
            $items['freeItems'][] = $item->toArray();
        }

        return $items;
    }
}
