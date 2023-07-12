<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\FoodTypes;

final class Vegetable extends Product
{

    public function getType(): FoodTypes
    {
        return FoodTypes::VEGETABLE;
    }
}
