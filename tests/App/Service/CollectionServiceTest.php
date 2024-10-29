<?php

namespace Tests\App\Service;

use App\Collection\FruitCollection;
use App\Collection\VegetableCollection;
use App\DTO\FruitDTO;
use App\DTO\VegetableDTO;
use App\Enum\ProduceEnum;
use App\Service\CollectionService;
use PHPUnit\Framework\TestCase;

class CollectionServiceTest extends TestCase
{
    private FruitCollection $fruitCollection;
    private VegetableCollection $vegetableCollection;
    private CollectionService $service;

    protected function setUp(): void
    {
        // Mock the collections
        $this->fruitCollection = $this->createMock(FruitCollection::class);
        $this->vegetableCollection = $this->createMock(VegetableCollection::class);

        // Initialize CollectionService with the mocked collections
        $this->service = new CollectionService($this->fruitCollection, $this->vegetableCollection);
    }

    public function testGetCollection(): void
    {
        // Arrange: Prepare the collection data
        $fruits = [new FruitDTO('Apple', 150)];
        $this->fruitCollection->expects($this->once())
            ->method('list')
            ->willReturn($fruits);

        // Act: Call getCollection with ProduceEnum::FRUIT
        $result = $this->service->getCollection(ProduceEnum::FRUIT);

        // Assert: Verify the result
        $this->assertCount(1, $result);
        $this->assertEquals('Apple', $result[0]->name);
        $this->assertEquals(150, $result[0]->weight);
    }

    public function testAddToCollection(): void
    {
        // Arrange: Define the item to add
        $name = 'Banana';
        $weight = 120;

        // Expect the collection's add method to be called with specified parameters
        $this->fruitCollection->expects($this->once())
            ->method('add')
            ->with($name, $weight);

        // Act: Call addToCollection with ProduceEnum::FRUIT
        $this->service->addToCollection(ProduceEnum::FRUIT, $name, $weight);
    }

    public function testRemoveFromCollection(): void
    {
        // Arrange: Define the item to remove
        $name = 'Carrot';
        $this->vegetableCollection->expects($this->once())
            ->method('list')
            ->willReturn([new VegetableDTO('Carrot', 80)]);
        
        $this->vegetableCollection->expects($this->once())
            ->method('remove')
            ->with($name);

        // Act: Call removeFromCollection with ProduceEnum::VEGETABLE
        $result = $this->service->removeFromCollection(ProduceEnum::VEGETABLE, $name);
    
        // Assert: Verify that removal was successful
        $this->assertTrue($result);
    }

    public function testRemoveFromCollectionNotFound(): void
    {
        // Arrange: Set up the vegetable collection to be empty
        $this->vegetableCollection->expects($this->once())
            ->method('list')
            ->willReturn([]);
        
        $this->vegetableCollection->expects($this->never())
            ->method('remove');

        // Act: Try to remove an item that doesn't exist with ProduceEnum::VEGETABLE
        $result = $this->service->removeFromCollection(ProduceEnum::VEGETABLE, 'Tomato');

        // Assert: Verify that the removal was unsuccessful
        $this->assertFalse($result);
    }
}
