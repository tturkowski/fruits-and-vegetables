<?php

declare(strict_types=1);

namespace App\Tests\App\Utils\ProductHydrator;

use App\Dto\Product;
use App\Enum\FoodTypes;
use App\Enum\MassMeasuresUnits;
use App\Util\ProductHydrator\Exception\WrongProductData;
use App\Util\ProductHydrator\NaiveHydrator;
use PHPUnit\Framework\TestCase;

class NaiveHydratorTest extends TestCase
{

    /**
     * @dataProvider productsDataProvider
     */
    public function testHydrateProduct(array $item, bool $causeException, ?Product $result): void
    {
        $hydrator = new NaiveHydrator();

        if ($causeException) {
            $id = $item['id'] ?? null;
            $name = $item['name'] ?? null;
            $this->expectExceptionObject(
                new WrongProductData("Wrong product item definition in JSON request. id: $id; name: $name")
            );
        }

        $productDto = $hydrator->hydrateProduct($item);

        $this->assertEquals($result, $productDto);
    }

    public function productsDataProvider(): array
    {
        return [
            [
                'item' => [
                    'id' => 1,
                    'name' => 'Apple',
                    'quantity' => 2500,
                    'type' => FoodTypes::FRUIT->value,
                    'unit' => MassMeasuresUnits::GRAM->value,
                ],
                'causeException' => false,
                'result' => new Product(1, 'Apple', 2500, FoodTypes::FRUIT, MassMeasuresUnits::GRAM),
            ],
            [
                'item' => [
                    'id' => 2,
                    'name' => 'Carrot',
                    'quantity' => 40,
                    'type' => FoodTypes::VEGETABLE->value,
                    'unit' => MassMeasuresUnits::KILO->value,
                ],
                'causeException' => false,
                'result' => new Product(2, 'Carrot', 40, FoodTypes::VEGETABLE, MassMeasuresUnits::KILO),
            ],
            [
                'item' => [
                    'id' => 1,
                    'name' => '',   //empty name
                    'quantity' => 2500,
                    'type' => FoodTypes::FRUIT->value,
                    'unit' => MassMeasuresUnits::GRAM->value,
                ],
                'causeException' => true,
                'result' => null,

            ],
            [
                'item' => [
                    'id' => 1,
                    'name' => 'Apple',
                    //'quantity' => 2500, missing field
                    'type' => FoodTypes::FRUIT->value,
                    'unit' => MassMeasuresUnits::GRAM->value,
                ],
                'causeException' => true,
                'result' => null,
            ],
            [
                'item' => [
                    'id' => 1,
                    'name' => 'Apple',
                    'quantity' => 2500,
                    'type' => 'martian food',   //unknown food type
                    'unit' => MassMeasuresUnits::GRAM->value,
                ],
                'causeException' => true,
                'result' => null,
            ],
            [
                'item' => [
                    'id' => 1,
                    'name' => 'Apple',
                    'quantity' => 2500,
                    'type' => FoodTypes::FRUIT->value,
                    'unit' => 'yottagramme', //unknown mass unit
                ],
                'causeException' => true,
                'result' => null,
            ],
        ];
    }
}
