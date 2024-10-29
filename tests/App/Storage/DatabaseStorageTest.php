<?php

namespace Tests\Storage;

use App\DataFixtures\AppFixtures;
use App\Enum\ProduceEnum;
use App\Storage\DatabaseStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class DatabaseStorageTest extends KernelTestCase
{
    use ResetDatabase, Factories;
    
    private DatabaseStorage $storage;
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        
        $this->entityManager = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        
        $fixture = new AppFixtures;
        $fixture->load($this->entityManager);
        
        $this->storage = new DatabaseStorage($this->entityManager);
    }

    public function testListFruits(): void
    {
        $fruits = $this->storage->list(ProduceEnum::FRUIT->value);

        $this->assertCount(3, $fruits, 'There should be 3 fruits in the list');
        
        $this->assertEquals('Apple', $fruits[0]->name);
        $this->assertEquals(150, $fruits[0]->weight);
    
        $this->assertEquals('Banana', $fruits[1]->name);
        $this->assertEquals(120, $fruits[1]->weight);
    
        $this->assertEquals('Green Apple', $fruits[2]->name);
        $this->assertEquals(300, $fruits[2]->weight);
    }
    
    public function testListVegetables(): void
    {
        $vegetables = $this->storage->list(ProduceEnum::VEGETABLE->value);
        
        $this->assertCount(2, $vegetables, 'There should be 2 vegetables in the list');
        
        $this->assertEquals('Carrot', $vegetables[0]->name);
        $this->assertEquals(80, $vegetables[0]->weight);
    
        $this->assertEquals('Tomato', $vegetables[1]->name);
        $this->assertEquals(100, $vegetables[1]->weight);
    }

    public function testAddFruit(): void
    {
        $newFruitItem = ['name' => 'Pineapple', 'weight' => 1000];

        $this->storage->add('fruit', $newFruitItem);

        $fruits = $this->storage->list(ProduceEnum::FRUIT->value);

        $this->assertCount(4, $fruits, 'There should be 4 fruits in the list after adding a new one');

        $addedFruit = $fruits[3];

        $this->assertEquals('Pineapple', $addedFruit->name);
        $this->assertEquals(1000, $addedFruit->weight);
    }

    public function testAddFruitMissingAttributes(): void
    {
        $missingNameFruit = ['weight' => 500];
        $missingWeightFruit = ['name' => 'Grapes'];

        $this->expectException(\InvalidArgumentException::class);
        $this->storage->add('fruit', $missingNameFruit);

        $this->expectException(\InvalidArgumentException::class);
        $this->storage->add('fruit', $missingWeightFruit);
    }

    public function testAddVegetable(): void
    {
        $newVegetableItem = ['name' => 'Broccoli', 'weight' => 300];

        $this->storage->add('vegetable', $newVegetableItem);

        $vegetables = $this->storage->list(ProduceEnum::VEGETABLE->value);

        $this->assertCount(3, $vegetables, 'There should be 3 vegetables in the list after adding a new one');

        $addedVegetable = $vegetables[2];

        $this->assertEquals('Broccoli', $addedVegetable->name);
        $this->assertEquals(300, $addedVegetable->weight);
    }

    public function testAddVegetableMissingAttributes(): void
    {
        $missingNameVegetable = ['weight' => 200];
        $missingWeightVegetable = ['name' => 'Spinach'];

        $this->expectException(\InvalidArgumentException::class);
        $this->storage->add('vegetable', $missingNameVegetable);

        $this->expectException(\InvalidArgumentException::class);
        $this->storage->add('vegetable', $missingWeightVegetable);
    }

    public function testDeleteFruitByName(): void
    {
        $this->storage->remove(ProduceEnum::FRUIT->value, 'Apple');

        $fruits = $this->storage->list(ProduceEnum::FRUIT->value);

        $this->assertCount(2, $fruits, 'There should be 2 fruits in the list after deletion');
    }

    public function testDeleteVegetableByName(): void
    {
        $this->storage->remove(ProduceEnum::VEGETABLE->value, 'Carrot');

        $vegetables = $this->storage->list(ProduceEnum::VEGETABLE->value);

        $this->assertCount(1, $vegetables, 'There should be 1 vegetable in the list after deletion');
    }

    public function testDeleteNonExistentFruitByName(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->storage->remove(ProduceEnum::FRUIT->value, 'NonExistentFruit');
    }
   
    public function testDeleteNonExistentVegetableByName(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->storage->remove(ProduceEnum::VEGETABLE->value, 'NonExistentVeg');
    }

    public function testFilterFruitsByName(): void
    {
        $filters = ['name' => 'Apple'];
        $fruits = $this->storage->list(ProduceEnum::FRUIT->value, $filters);
        
        $this->assertCount(2, $fruits, 'There should be 1 fruit in the list filtered by name');
        $this->assertEquals('Apple', $fruits[0]->name);
        $this->assertEquals('Green Apple', $fruits[1]->name);
    }

    public function testFilterVegetablesByName(): void
    {
        $filters = ['name' => 'Carrot'];
        $vegetables = $this->storage->list(ProduceEnum::VEGETABLE->value, $filters);
        
        $this->assertCount(1, $vegetables, 'There should be 1 vegetable in the list filtered by name');
        $this->assertEquals('Carrot', $vegetables[0]->name);
    }

    public function testFilterFruitsByWeight(): void
    {
        $filters = ['weight' => 150];
        $fruits = $this->storage->list(ProduceEnum::FRUIT->value, $filters);
        
        $this->assertCount(1, $fruits, 'There should be 1 fruit in the list filtered by weight');
        foreach ($fruits as $fruit) {
            $this->assertEquals(150, $fruit->weight);
        }
    }

    public function testFilterVegetablesByWeight(): void
    {
        $filters = ['weight' => 80];
        $vegetables = $this->storage->list(ProduceEnum::VEGETABLE->value, $filters);
        
        $this->assertCount(1, $vegetables, 'There should be 1 vegetable in the list filtered by weight');
        $this->assertEquals('Carrot', $vegetables[0]->name);
    }

    public function testFilterFruitsByNameAndWeight(): void
    {
        $filters = ['name' => 'Apple', 'weight' => 150];
        $fruits = $this->storage->list(ProduceEnum::FRUIT->value, $filters);
        
        $this->assertCount(1, $fruits, 'There should be 1 fruit in the list filtered by name and weight');
        $this->assertEquals('Apple', $fruits[0]->name);
    }

    public function testFilterVegetablesByNameAndWeight(): void
    {
        $filters = ['name' => 'Tomato', 'weight' => 100];
        $vegetables = $this->storage->list(ProduceEnum::VEGETABLE->value, $filters);
        
        $this->assertCount(1, $vegetables, 'There should be 1 vegetable in the list filtered by name and weight');
        $this->assertEquals('Tomato', $vegetables[0]->name);
    }
}
