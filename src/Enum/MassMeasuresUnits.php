<?php

declare(strict_types=1);

namespace App\Enum;

enum MassMeasuresUnits: string
{
    case GRAM = 'g';
    case  KILO = 'kg';

    public function getMultiplier(): int
    {
        return match ($this) {
            self::GRAM => 1,
            self::KILO => 1000,
        };
    }
}
