<?php

declare(strict_types=1);

namespace App\Tests\App\Enum;

use App\Enum\MassMeasuresUnits;
use PHPUnit\Framework\TestCase;

final class MassMeasuresUnitsTest extends TestCase
{

    public function testGetMultiplier(): void
    {
        $gram = MassMeasuresUnits::from('g');
        $this->assertEquals(1, $gram->getMultiplier());
        $kilo = MassMeasuresUnits::from('kg');
        $this->assertEquals(1000, $kilo->getMultiplier());
    }
}
