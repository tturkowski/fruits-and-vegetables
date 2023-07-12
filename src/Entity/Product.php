<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\FoodTypes;
use App\Enum\MassMeasuresUnits;

class Product implements ProductInterface
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly int $quantity,
        private readonly FoodTypes $type,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getConvertedQuantity(MassMeasuresUnits $units = MassMeasuresUnits::GRAM): float
    {
        return ((float)$this->quantity) / $units->getMultiplier();
    }
    public function getType(): FoodTypes
    {
        return $this->type;
    }

}