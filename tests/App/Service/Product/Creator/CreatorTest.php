<?php

declare(strict_types=1);

namespace App\Tests\App\Service\Product\Creator;

use App\Dto\Product;
use App\Entity\Fruit;
use App\Entity\Vegetable;
use App\Enum\FoodTypes;
use App\Enum\MassMeasuresUnits;
use App\Service\Product\Creator\Creator;
use App\Service\Product\Creator\Exception\UnknownProductType;
use PHPUnit\Framework\TestCase;

final class CreatorTest extends TestCase
{

    public function testCreateProductException(): void
    {
        $allowedCases = [FoodTypes::FRUIT, FoodTypes::VEGETABLE];
        $cases = FoodTypes::cases();
        $unknownCases = array_udiff(
            $cases,
            $allowedCases,
            static fn (FoodTypes $item1, FoodTypes $item2) => $item1->name <=> $item2->name,
        );
        if (empty($unknownCases)) {
            $this->assertEmpty($unknownCases);
            return;
        }

        $creator = new Creator();
        $productDto = new Product(1, 'Apple', 1000, array_shift($unknownCases), MassMeasuresUnits::GRAM);
        $this->expectExceptionObject(new UnknownProductType('Creator cannot create product with type: ' . $productDto->getType()->value));

        $creator->createProduct($productDto);
    }

    /**
     * @dataProvider productsProvider
     */
    public function testCreateProduct(
        int $id,
        string $name,
        int $quantity,
        FoodTypes $type,
        MassMeasuresUnits $unit
    ): void {
        $creator = new Creator();
        $productDto = new Product($id, $name, $quantity, $type, $unit);
        $product = $creator->createProduct($productDto);

        $productClass = match($type) {
            FoodTypes::FRUIT => Fruit::class,
            FoodTypes::VEGETABLE => Vegetable::class,
        };

        $this->assertInstanceOf($productClass, $product);
        $this->assertEquals($type, $product->getType());

        $productQuantity = $quantity * match($unit) {
            MassMeasuresUnits::GRAM => 1,
            MassMeasuresUnits::KILO => 1000,
        };

        $this->assertEquals($productQuantity, $product->getQuantity());
        $this->assertEquals($name, $product->getName());
        $this->assertEquals($id, $product->getId());
    }

    public function productsProvider(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Apple',
                'quantity' => 2500,
                'type' => FoodTypes::FRUIT,
                'unit' => MassMeasuresUnits::GRAM,
            ],
            [
                'id' => 2,
                'name' => 'Carrot',
                'quantity' => 2500,
                'type' => FoodTypes::VEGETABLE,
                'unit' => MassMeasuresUnits::GRAM,
            ],
            [
                'id' => 3,
                'name' => 'Orange',
                'quantity' => -400,
                'type' => FoodTypes::FRUIT,
                'unit' => MassMeasuresUnits::KILO,
            ],
        ];
    }
}