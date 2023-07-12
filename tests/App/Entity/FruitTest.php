<?php

declare(strict_types=1);

namespace App\Tests\App\Entity;

use App\Entity\Fruit;
use App\Enum\MassMeasuresUnits;
use PHPUnit\Framework\TestCase;

final class FruitTest extends TestCase
{

    /**
     * @dataProvider fruitsProvider
     */
    public function testFruitEntity($id, $name, $quantity): void
    {
        $product = new Fruit($id, $name, $quantity);

        $this->assertEquals($id, $product->getId());
        $this->assertEquals($name, $product->getName());
        $this->assertEquals($quantity, $product->getQuantity());
        $this->assertEquals((float)$quantity, $product->getConvertedQuantity());
        $this->assertEquals((float)$quantity, $product->getConvertedQuantity(MassMeasuresUnits::GRAM));
        $this->assertEquals($quantity / 1000, $product->getConvertedQuantity(MassMeasuresUnits::KILO));
    }

    public function fruitsProvider(): array
    {
        return [
            ['id' => 1, 'name' => 'Apple', 'quantity' => 2500],
            ['id' => 13, 'name' => '', 'quantity' => 2500],
            ['id' => -13, 'name' => 'Apple', 'quantity' => -400],
        ];
    }

}