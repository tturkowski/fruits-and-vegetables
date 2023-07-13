<?php

declare(strict_types=1);

namespace App\Tests\App\Utils\ProductCollection;

use App\Entity\Fruit;
use App\Entity\Vegetable;
use App\Enum\SearchFields;
use App\Util\ProductCollection\Collection;
use PHPUnit\Framework\TestCase;

final class CollectionTest extends TestCase
{
    private const FRUIT_ID = 1;
    private const FRUIT_NAME = 'Apple';
    private const FRUIT_QUANTITY = 1000;
    private const VEGETABLE_ID = 2;
    private const VEGETABLE_NAME = 'Carrot';
    private const VEGETABLE_QUANTITY = 2000;

    private const NON_EXISTED_ID = 3;

    private  const  ONION_ID = 4;
    private  const  ONION_NAME = 'Onion';
    private  const  ONION_QUANTITY = 1500;

    public function testCollectionAdd(): Collection
    {
        $collection = new Collection();
        $this->assertEmpty($collection->list());

        $fruit = new Fruit(self::FRUIT_ID, self::FRUIT_NAME, self::FRUIT_QUANTITY);
        $collection->add($fruit);
        $this->assertCount(1, $collection->list());
        $this->assertEquals([$fruit], $collection->list());

        $collection->add($fruit);
        $this->assertCount(2, $collection->list());
        $this->assertEquals([$fruit, $fruit], $collection->list());

        $vegetable = new Vegetable(self::VEGETABLE_ID, self::VEGETABLE_NAME, self::VEGETABLE_QUANTITY);
        $collection->add($vegetable);
        $this->assertCount(3, $collection->list());
        $this->assertEquals([$fruit, $fruit, $vegetable], $collection->list());
        return $collection;
    }

    /**
     * @depends testCollectionAdd
     */
    public function testCollectionRemove(Collection $collection): void
    {
        $removedFruits = $collection->remove(self::FRUIT_ID);
        $this->assertEquals(2, $removedFruits);
        $this->assertCount(1, $collection->list());
        $vegetable = $collection->list()[0];

        $this->assertEquals(self::VEGETABLE_ID, $vegetable->getId());
        $this->assertEquals(self::VEGETABLE_NAME, $vegetable->getName());
        $this->assertEquals(self::VEGETABLE_QUANTITY, $vegetable->getQuantity());

        $removedUnknownProducts = $collection->remove(self::NON_EXISTED_ID);

        $this->assertEquals(0, $removedUnknownProducts);
        $this->assertCount(1, $collection->list());

        $removedVegetables = $collection->remove(self::VEGETABLE_ID);
        $this->assertEquals(1, $removedVegetables);
        $this->assertEmpty($collection->list());
    }

    public function testCollectionSearch(): void
    {
        $collection = new Collection();

        $fruit = new Fruit(self::FRUIT_ID, self::FRUIT_NAME, self::FRUIT_QUANTITY);
        $collection->add($fruit);
        $collection->add($fruit);
        $vegetable = new Vegetable(self::VEGETABLE_ID, self::VEGETABLE_NAME, self::VEGETABLE_QUANTITY);
        $collection->add($vegetable);

        $unknownProducts = $collection->search(SearchFields::ID, ['id' => self::NON_EXISTED_ID]);
        $this->assertCount(0, $unknownProducts);

        $apples = $collection->search(SearchFields::ID, ['id' => self::FRUIT_ID]);
        $this->assertCount(2, $apples);
        $this->assertEquals([$fruit, $fruit], $apples);

        $vegetables = $collection->search(SearchFields::NAME, ['name' => self::VEGETABLE_NAME]);
        $this->assertCount(1, $vegetables);
        $this->assertEquals([$vegetable], $vegetables);

        $lightWeight = $collection->search(
            SearchFields::QUANTITY,
            ['max' => (self::FRUIT_QUANTITY + self::VEGETABLE_QUANTITY) / 2]
        );
        $this->assertCount(2, $lightWeight);
        $this->assertEquals([$fruit, $fruit], $lightWeight);

        $heavyWeight = $collection->search(
            SearchFields::QUANTITY,
            ['min' => (self::FRUIT_QUANTITY + self::VEGETABLE_QUANTITY) / 2]
        );
        $this->assertCount(1, $heavyWeight);
        $this->assertEquals([$vegetable], $heavyWeight);


        $onion = new Vegetable(self::ONION_ID, self::ONION_NAME, self::ONION_QUANTITY);
        $collection->add($onion);
        $middleWeight = $collection->search(
            SearchFields::QUANTITY,
            [
                'min' => self::ONION_QUANTITY - 1,
                'max' => self::ONION_QUANTITY + 1,
            ]
        );

        $this->assertCount(1, $middleWeight);
        $this->assertEquals([$onion], $middleWeight);

        unset($apples, $vegetables, $lightWeight, $middleWeight);
        $this->assertCount(4, $collection->list());
    }

}
