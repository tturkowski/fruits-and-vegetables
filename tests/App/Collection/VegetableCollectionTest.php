<?php

namespace Tests\App\Collection;

use App\Collection\VegetableCollection;
use App\DTO\VegetableDTO;
use App\Storage\DatabaseStorage;
use PHPUnit\Framework\TestCase;

class VegetableCollectionTest extends TestCase
{
    private VegetableCollection $vegetableCollection;
    private DatabaseStorage $storage;

    protected function setUp(): void
    {
        // Mock the DatabaseStorage
        $this->storage = $this->createMock(DatabaseStorage::class);
        
        // Initialize VegetableCollection with the mocked storage
        $this->vegetableCollection = new VegetableCollection($this->storage);
    }

    public function testAddVegetable(): void
    {
        // Define test parameters
        $vegetableName = 'Carrot';
        $weight = 200;
    
        // Expect the storage's add method to be called once with the specified parameters
        $this->storage->expects($this->once())
            ->method('add')
            ->with('vegetable', ['name' => $vegetableName, 'weight' => $weight]);
    
        // Call the add method
        $this->vegetableCollection->add($vegetableName, $weight);
    
        // Verify that the in-memory vegetables list has the newly added vegetable
        $vegetables = $this->vegetableCollection->list();
        $this->assertCount(1, $vegetables); // Ensure the list has one vegetable
        $this->assertEquals($vegetableName, $vegetables[0]->name); // Check the name
        $this->assertEquals($weight, $vegetables[0]->weight); // Check the weight
    }

    public function testRemoveVegetable(): void
    {
        // Define the vegetable to be removed
        $vegetableName = 'Broccoli';
    
        // Pre-populate the in-memory vegetables list with the vegetable to be removed
        $this->vegetableCollection->add($vegetableName, 150); // Add it first for the test context
    
        // Expect the storage's remove method to be called once
        $this->storage->expects($this->once())
            ->method('remove')
            ->with('vegetable', $vegetableName);
    
        // Call the remove method
        $this->vegetableCollection->remove($vegetableName);
    
        // Verify that the in-memory vegetables list no longer contains the removed vegetable
        $vegetables = $this->vegetableCollection->list();
        $this->assertCount(0, $vegetables); // Ensure the list is now empty
    }

    public function testListVegetables(): void
    {
        // Prepare initial data
        $vegetables = [
            new VegetableDTO('Carrot', 200),
            new VegetableDTO('Broccoli', 150),
        ];
    
        // Mock the storage to return the initial vegetables
        $this->storage->expects($this->once())
            ->method('list')
            ->with('vegetable')
            ->willReturn($vegetables);
    
        // Initialize the VegetableCollection with the mocked storage
        $this->vegetableCollection = new VegetableCollection($this->storage);
    
        // Call the list method
        $result = $this->vegetableCollection->list();
    
        // Verify the count and the values
        $this->assertCount(2, $result);
        $this->assertEquals('Carrot', $result[0]->name);
        $this->assertEquals(200, $result[0]->weight);
        $this->assertEquals('Broccoli', $result[1]->name);
        $this->assertEquals(150, $result[1]->weight);
    }
}
