<?php

declare(strict_types=1);

namespace App\Tests\App\Entity;

use App\Entity\Vegetable;
use App\Enum\MassMeasuresUnits;
use PHPUnit\Framework\TestCase;

final class VegetableTest extends TestCase
{

    /**
     * @dataProvider vegetablesProvider
     */
    public function testVegetableEntity(int $id, string $name, int $quantity): void
    {
        $product = new Vegetable($id, $name, $quantity);

        $this->assertEquals($id, $product->getId());
        $this->assertEquals($name, $product->getName());
        $this->assertEquals($quantity, $product->getQuantity());
        $this->assertSame((float)$quantity, $product->getConvertedQuantity());
        $this->assertSame((float)$quantity, $product->getConvertedQuantity(MassMeasuresUnits::GRAM));
        $this->assertSame($quantity / 1000, $product->getConvertedQuantity(MassMeasuresUnits::KILO));
    }

    public function vegetablesProvider(): array
    {
        return [
            ['id' => 1, 'name' => 'Carrot', 'quantity' => 2500],
            ['id' => 13, 'name' => '', 'quantity' => 2500],
            ['id' => -13, 'name' => 'Potato', 'quantity' => -400],
        ];

    }
}
