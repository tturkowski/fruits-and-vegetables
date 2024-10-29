<?php

namespace Tests\App\Collection;

use App\Collection\FruitCollection;
use App\DataFixtures\AppFixtures;
use App\Storage\DatabaseStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class FruitCollectionTest extends KernelTestCase
{
    use ResetDatabase, Factories;

    private DatabaseStorage $storage;
    private FruitCollection $fruitCollection;
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        
        $this->entityManager = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        
        $fixture = new AppFixtures();
        $fixture->load($this->entityManager);
        
        $this->storage = new DatabaseStorage($this->entityManager);
        $this->fruitCollection = new FruitCollection($this->storage);
    }

    public function testAddFruit(): void
    {
        $this->fruitCollection->add('Mango', 200);
        
        $fruits = $this->fruitCollection->list();
        $this->assertCount(4, $fruits);
        $this->assertEquals('Mango', $fruits[3]->name);
        $this->assertEquals(200, $fruits[3]->weight);
    }

    public function testRemoveFruit(): void
    {
        $result = $this->fruitCollection->remove('Apple');

        $this->assertTrue($result);

        $fruits = $this->fruitCollection->list();
        
        $this->assertCount(2, $fruits);
        $this->assertFalse(array_search('Apple', array_column($fruits, 'name')) !== false);
    }

    public function testListFruitsWithFilters(): void
    {
        $filters = ['name' => 'Apple'];
        
        $fruits = $this->fruitCollection->list($filters);
        $this->assertCount(2, $fruits);
        $this->assertEquals('Apple', $fruits[0]->name);
    }

    public function testListFruitsWithWeightFilter(): void
    {
        $filters = ['weight' => 150];
        
        $fruits = $this->fruitCollection->list($filters);
        $this->assertCount(1, $fruits);
        $this->assertEquals('Apple', $fruits[0]->name);
    }

    public function testListFruitsWithMultipleFilters(): void
    {
        $filters = ['name' => 'Apple', 'weight' => 150];
        
        $fruits = $this->fruitCollection->list($filters);
        $this->assertCount(1, $fruits);
        $this->assertEquals('Apple', $fruits[0]->name);
    }

    public function testSearchFruit(): void
    {    
        $fruits = $this->fruitCollection->search('Banana');
        $this->assertCount(1, $fruits);
        $this->assertEquals('Banana', $fruits[0]->name);
    }
}
