<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\FoodTypes;
use App\Enum\MassMeasuresUnits;

interface ProductInterface
{
    public function getId(): int;

    public function getName(): string;

    /**
     * @return int Always in MassMeasuresUnits::GRAM
     */
    public function getQuantity(): int;

    public function getConvertedQuantity(MassMeasuresUnits $units = MassMeasuresUnits::GRAM): float;

    public function getType(): FoodTypes;
}
