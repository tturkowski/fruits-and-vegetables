<?php

declare(strict_types=1);

namespace App\Tests\App\Utils\ProductCollection;

use App\Entity\Fruit;
use App\Entity\Vegetable;
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
    public function testCollectionAdd(): Collection
    {
        $collection = new Collection();
        $this->assertEmpty($collection->list());

        $fruit = new Fruit(self::FRUIT_ID, self::FRUIT_NAME, self::FRUIT_QUANTITY);
        $collection->add($fruit);
        $this->assertCount(1, $collection->list());
        $this->assertEquals([$fruit], iterator_to_array($collection->list()));

        $collection->add($fruit);
        $this->assertCount(2, $collection->list());
        $this->assertEquals([$fruit, $fruit], iterator_to_array($collection->list()));

        $vegetable = new Vegetable(self::VEGETABLE_ID, self::VEGETABLE_NAME, self::VEGETABLE_QUANTITY);
        $collection->add($vegetable);
        $this->assertCount(3, $collection->list());
        $this->assertEquals([$fruit, $fruit, $vegetable], iterator_to_array($collection->list()));
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
        $vegetable = array_values(iterator_to_array($collection->list()))[0];

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

}
