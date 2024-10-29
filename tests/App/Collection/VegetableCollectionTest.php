<?php

namespace Tests\App\Collection;

use App\Collection\VegetableCollection;
use App\DataFixtures\AppFixtures;
use App\Storage\DatabaseStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class VegetableCollectionTest extends KernelTestCase
{
    use ResetDatabase, Factories;

    private DatabaseStorage $storage;
    private VegetableCollection $vegetableCollection;
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
        $this->vegetableCollection = new VegetableCollection($this->storage);
    }

    public function testAddVegetable(): void
    {
        $this->vegetableCollection->add('Cucumber', 300);
        
        $vegetables = $this->vegetableCollection->list();
        $this->assertCount(3, $vegetables);
        $this->assertEquals('Cucumber', $vegetables[2]->name);
        $this->assertEquals(300, $vegetables[2]->weight);
    }

    public function testRemoveVegetable(): void
    {
        $this->vegetableCollection->remove('Carrot');
        
        $vegetables = $this->vegetableCollection->list();
        $this->assertCount(1, $vegetables);
        $this->assertFalse(array_search('Carrot', array_column($vegetables, 'name')) !== false);
    }

    public function testListVegetablesWithFilters(): void
    {
        $filters = ['name' => 'Tomato'];
        
        $vegetables = $this->vegetableCollection->list($filters);
        $this->assertCount(1, $vegetables);
        $this->assertEquals('Tomato', $vegetables[0]->name);
    }

    public function testListVegetablesWithWeightFilter(): void
    {
        $filters = ['weight' => 100];
        
        $vegetables = $this->vegetableCollection->list($filters);
        $this->assertCount(1, $vegetables);
        $this->assertEquals('Tomato', $vegetables[0]->name);
    }

    public function testListVegetablesWithMultipleFilters(): void
    {
        $filters = ['name' => 'Tomato', 'weight' => 100];
        
        $vegetables = $this->vegetableCollection->list($filters);
        $this->assertCount(1, $vegetables);
        $this->assertEquals('Tomato', $vegetables[0]->name);
    }

    public function testSearchVegetables(): void
    {    
        $vegetables = $this->vegetableCollection->search('Tomato');
        $this->assertCount(1, $vegetables);
        $this->assertEquals('Tomato', $vegetables[0]->name);
    }
}
