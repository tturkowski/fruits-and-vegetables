<?php

namespace Tests\App\Collection;

use App\Storage\DatabaseStorage;
use App\Collection\FruitCollection;
use App\DTO\FruitDTO;
use PHPUnit\Framework\TestCase;

class FruitCollectionTest extends TestCase
{
    private FruitCollection $fruitCollection;
    private DatabaseStorage $storage;

    protected function setUp(): void
    {
        // Mock the DatabaseStorage
        $this->storage = $this->createMock(DatabaseStorage::class);
        
        // Initialize FruitCollection with the mocked storage
        $this->fruitCollection = new FruitCollection($this->storage);
    }

    public function testAddFruit(): void
    {
        // Define test parameters
        $fruitName = 'Apple';
        $weight = 150;
    
        // Expect the storage's add method to be called once with the specified parameters
        $this->storage->expects($this->once())
            ->method('add')
            ->with('fruit', ['name' => $fruitName, 'weight' => $weight]);
    
        // Call the add method
        $this->fruitCollection->add($fruitName, $weight);
    
        // Verify that the in-memory fruits list has the newly added fruit
        $fruits = $this->fruitCollection->list();
        $this->assertCount(1, $fruits); // Ensure the list has one fruit
        $this->assertEquals($fruitName, $fruits[0]->name); // Check the name
        $this->assertEquals($weight, $fruits[0]->weight); // Check the weight
    }

    public function testRemoveFruit(): void
    {
        // Define the fruit to be removed
        $fruitName = 'Banana';
    
        // Pre-populate the in-memory fruits list with the fruit to be removed
        $this->fruitCollection->add($fruitName, 120); // Add it first for the test context
    
        // Expect the storage's remove method to be called once
        $this->storage->expects($this->once())
            ->method('remove')
            ->with('fruit', $fruitName);
    
        // Call the remove method
        $this->fruitCollection->remove($fruitName);
    
        // Verify that the in-memory fruits list no longer contains the removed fruit
        $fruits = $this->fruitCollection->list();
        $this->assertCount(0, $fruits); // Ensure the list is now empty
    }
    

    public function testListFruits(): void
    {
        // Prepare initial data
        $fruits = [
            new FruitDTO('Apple', 150),
            new FruitDTO('Banana', 120),
        ];
    
        // Mock the storage to return the initial fruits
        $this->storage->expects($this->once())
            ->method('list')
            ->with('fruit')
            ->willReturn($fruits);
    
        // Create the FruitCollection instance
        $this->fruitCollection = new FruitCollection($this->storage);
    
        // Call the list method
        $result = $this->fruitCollection->list();
    
        // Verify the count and the values
        $this->assertCount(2, $result);
        $this->assertEquals('Apple', $result[0]->name);
        $this->assertEquals(150, $result[0]->weight);
        $this->assertEquals('Banana', $result[1]->name);
        $this->assertEquals(120, $result[1]->weight);
    }
}
