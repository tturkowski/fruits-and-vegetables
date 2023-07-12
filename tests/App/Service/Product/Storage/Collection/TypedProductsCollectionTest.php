<?php

declare(strict_types=1);

namespace App\Tests\App\Service\Product\Storage\Collection;

use App\Entity\Fruit;
use App\Entity\ProductInterface;
use App\Entity\Vegetable;
use App\Enum\FoodTypes;
use App\Service\Product\Storage\Collection\TypedProductsCollection;
use App\Service\Product\Storage\Exception\WrongProductType;
use App\Util\ProductCollection\Collection;
use PHPUnit\Framework\TestCase;

final class TypedProductsCollectionTest extends TestCase
{

    /**
     * @dataProvider productsProvider
     */
    public function testTypedCollection(
        ProductInterface $product1,
        ProductInterface $product2,
        ProductInterface $product3,
        FoodTypes $allowedType,
    ): void {
        $products = new TypedProductsCollection(new Collection(), $allowedType);
        $this->assertEmpty($products->list());

        $products->add($product1);
        $this->assertCount(1, $products->list());

        $products->add($product2);
        $this->assertCount(2, $products->list());

        $removedProductsCount = $products->remove(2);
        $this->assertEquals(1, $removedProductsCount);
        $this->assertCount(1, $products->list());

        $this->expectExceptionObject(
            new WrongProductType('Collection accepts only items with type: ' . $allowedType->value)
        );
        $products->add($product3);
    }


    public function productsProvider(): array
    {
        return [
            [
                'product1' => new Fruit(1, 'Apple', 1000),
                'product2' => new Fruit(2, 'Orange', 2000),
                'product3' => new Vegetable(3, 'Carrot', 3000),
                'allowedType' => FoodTypes::FRUIT,
            ],
            [
                'product1' => new Vegetable(1, 'Apple', 1000),
                'product2' => new Vegetable(2, 'Orange', 2000),
                'product3' => new Fruit(3, 'Carrot', 3000),
                'allowedType' => FoodTypes::VEGETABLE,
            ],
        ];
    }
}
