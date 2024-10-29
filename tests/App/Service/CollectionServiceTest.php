<?php

namespace Tests\App\Service;

use App\Collection\FruitCollection;
use App\Collection\VegetableCollection;
use App\Enum\ProduceEnum;
use App\Service\CollectionService;
use App\Storage\DatabaseStorage;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CollectionServiceTest extends KernelTestCase
{
    private CollectionService $collectionService;
    private FruitCollection $fruitCollection;
    private VegetableCollection $vegetableCollection;
    private DatabaseStorage $storage;

    protected function setUp(): void
    {
        // Mock the collections
        $this->fruitCollection = $this->createMock(FruitCollection::class);
        $this->vegetableCollection = $this->createMock(VegetableCollection::class);

        // Initialize CollectionService with the mocked collections
        $this->collectionService = new CollectionService($this->fruitCollection, $this->vegetableCollection);
    }

    public function testRemoveFromFruitCollectionSuccess(): void
    {
        $this->fruitCollection
            ->expects($this->once())
            ->method('list')
            ->willReturn([['name' => 'Apple', 'weight' => 150]]);
        
        $this->fruitCollection
            ->expects($this->once())
            ->method('remove')
            ->with('Apple');
        
        $result = $this->collectionService->removeFromCollection(ProduceEnum::FRUIT, 'Apple');

        $this->assertTrue($result);
    }

    public function testRemoveFromVegetableCollectionSuccess(): void
    {
        $this->vegetableCollection
            ->expects($this->once())
            ->method('list')
            ->willReturn([['name' => 'Carrot', 'weight' => 80]]);
        
        $this->vegetableCollection
            ->expects($this->once())
            ->method('remove')
            ->with('Carrot');
        
        $result = $this->collectionService->removeFromCollection(ProduceEnum::VEGETABLE, 'Carrot');

        $this->assertTrue($result);
    }

    public function testRemoveNonexistentFruitThrowsException(): void
    {
        $this->fruitCollection
            ->expects($this->once())
            ->method('list')
            ->willReturn([]);

        $this->fruitCollection
            ->expects($this->never())
            ->method('remove');
        
        $result = $this->collectionService->removeFromCollection(ProduceEnum::FRUIT, 'Nonexistent');

        $this->assertFalse($result);
    }

    public function testRemoveNonexistentVegetableThrowsException(): void
    {
        $this->vegetableCollection
            ->expects($this->once())
            ->method('list')
            ->willReturn([]);

        $this->vegetableCollection
            ->expects($this->never())
            ->method('remove');
        
        $result = $this->collectionService->removeFromCollection(ProduceEnum::VEGETABLE, 'Nonexistent');

        $this->assertFalse($result);
    }
    public function testProcessJsonRequest(): void
    {
        $jsonFilePath = __DIR__ . '/../../../request.json';

        $data = json_decode(file_get_contents($jsonFilePath), true);
    
        $fruitCount = count(array_filter($data, fn($item) => $item['type'] === 'fruit'));
        $vegetableCount = count(array_filter($data, fn($item) => $item['type'] === 'vegetable'));
    
        $this->fruitCollection
            ->expects($this->exactly($fruitCount))
            ->method('add');
        
        $this->vegetableCollection
            ->expects($this->exactly($vegetableCount))
            ->method('add');
    
        $this->collectionService->processJsonRequest($jsonFilePath);
    }
    
}
