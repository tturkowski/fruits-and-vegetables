<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enum\FoodTypes;
use App\Enum\MassMeasuresUnits;

final class Product
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly int $quantity,
        private readonly FoodTypes $type,
        private readonly MassMeasuresUnits $unit,
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

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getType(): FoodTypes
    {
        return $this->type;
    }

    public function getUnit(): MassMeasuresUnits
    {
        return $this->unit;
    }

}
