<?php

namespace Tests\Storage;

use App\DataFixture\ProduceFixture;
use App\Entity\Fruit;
use App\Entity\Vegetable;
use App\Enum\ProduceEnum;
use App\Repository\FruitRepository;
use App\Repository\VegetableRepository;
use App\Storage\DatabaseStorage;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DatabaseStorageTest extends TestCase
{
    private DatabaseStorage $storage;
    private EntityManagerInterface $entityManager;
    private FruitRepository $fruitRepository;
    private VegetableRepository $vegetableRepository;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        
        $this->fruitRepository = $this->createMock(FruitRepository::class);
        $this->vegetableRepository = $this->createMock(VegetableRepository::class);
        
        $this->entityManager->method('getRepository')
        ->will($this->returnCallback(function($class) {
            if ($class === Fruit::class) {
                return $this->fruitRepository;
            }
                if ($class === Vegetable::class) {
                    return $this->vegetableRepository;
                }
                throw new \InvalidArgumentException("Unsupported class: $class");
            }));
            
        // Load fixtures
        $fixture = new ProduceFixture();
        $fixture->load($this->entityManager);
        
        $this->storage = new DatabaseStorage($this->entityManager);
    }

    public function testAddFruit(): void
    {
        $item = ['name' => 'Apple', 'weight' => 150];

        $expectedFruit = new Fruit();
        $expectedFruit->setName('Apple');
        $expectedFruit->setWeight(150);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->callback(function($fruit) use ($expectedFruit) {
                return $fruit->getName() === $expectedFruit->getName() &&
                       $fruit->getWeight() === $expectedFruit->getWeight();
            }));

        $this->entityManager->expects($this->once())
            ->method('flush');   

        $this->storage->add('fruit', $item);
    }

    public function testAddFruitWithInvalidData(): void
    {
        $item = ['weight' => 150];

        $this->expectException(InvalidArgumentException::class);
        $this->storage->add('fruit', $item);
    }

    public function testAddVegetableWithInvalidData(): void
    {
        $item = ['weight' => 150];

        $this->expectException(InvalidArgumentException::class);
        $this->storage->add('vegatable', $item);
    }

    public function testAddVegetable(): void
    {
        $item = ['name' => 'Carrot', 'weight' => 100];

        $expectedVegetable = new Vegetable();
        $expectedVegetable->setName('Carrot');
        $expectedVegetable->setWeight(100);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->callback(function($vegetable) use ($expectedVegetable) {
                return $vegetable->getName() === $expectedVegetable->getName() &&
                       $vegetable->getWeight() === $expectedVegetable->getWeight();
            }));

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->storage->add('vegetable', $item);
    }

    public function testRemoveFruit(): void
    {
        $fruitName = 'Apple';
        $fruit = new Fruit();
        $fruit->setName($fruitName);

        $this->fruitRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => $fruitName])
            ->willReturn($fruit);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($fruit);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $result = $this->storage->remove('fruit', $fruitName);
        $this->assertTrue($result);
    }

    public function testRemoveVegetable(): void
    {
        $vegetableName = 'Carrot';
        $vegetable = new Vegetable();
        $vegetable->setName($vegetableName);

        $this->vegetableRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => $vegetableName])
            ->willReturn($vegetable);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($vegetable);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $result = $this->storage->remove('vegetable', $vegetableName);
        $this->assertTrue($result);
    }

    public function testRemoveFruitNotFound(): void
    {
        $fruitName = 'Nonexistent Fruit';

        $this->fruitRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => $fruitName])
            ->willReturn(null);

        $result = $this->storage->remove('fruit', $fruitName);
        $this->assertFalse($result);
    }

    public function testRemoveVegetableNotFound(): void
    {
        $vegetableName = 'Nonexistent Vegetable';

        $this->vegetableRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['name' => $vegetableName])
            ->willReturn(null);

        $result = $this->storage->remove('vegetable', $vegetableName);
        $this->assertFalse($result);
    }

    public function testListFruits(): void
    {
        $fruit1 = new Fruit();
        $fruit1->setName('Apple');
        $fruit1->setWeight(150);

        $fruit2 = new Fruit();
        $fruit2->setName('Banana');
        $fruit2->setWeight(120);

        $this->fruitRepository->expects($this->once())
            ->method('findByFilters')
            ->willReturn([$fruit1, $fruit2]);

        $fruits = $this->storage->list('fruit');

        $this->assertCount(2, $fruits);
        $this->assertSame('Apple', $fruits[0]->name);
        $this->assertSame(150, $fruits[0]->weight);
        $this->assertSame('Banana', $fruits[1]->name);
        $this->assertSame(120, $fruits[1]->weight);
    }

    public function testListVegetables(): void
    {
        $vegetable1 = new Vegetable();
        $vegetable1->setName('Carrot');
        $vegetable1->setWeight(100);

        $vegetable2 = new Vegetable();
        $vegetable2->setName('Broccoli');
        $vegetable2->setWeight(200);

        $this->vegetableRepository->expects($this->once())
            ->method('findByFilters')
            ->willReturn([$vegetable1, $vegetable2]);

        $vegetables = $this->storage->list('vegetable');

        $this->assertCount(2, $vegetables);
        $this->assertSame('Carrot', $vegetables[0]->name);
        $this->assertSame(100, $vegetables[0]->weight);
        $this->assertSame('Broccoli', $vegetables[1]->name);
        $this->assertSame(200, $vegetables[1]->weight);
    }

    public function testListFilters(): void
    {
        $fruit1 = new Fruit();
        $fruit1->setName('Apple');
        $fruit1->setWeight(150);

        $fruit3 = new Fruit();
        $fruit3->setName('Green Apple');
        $fruit3->setWeight(150);

        $fruit2 = new Fruit();
        $fruit2->setName('Banana');
        $fruit2->setWeight(120);

        // Configure the fruit repository to return the created fruit objects
        $this->fruitRepository->expects($this->once())
            ->method('findByFilters')
            ->with(['name' => 'Apple']) // Ensure that filters are applied correctly
            ->willReturn([$fruit1, $fruit3]);

        // Call the storage's list method with the filter for fruits
        $fruits = $this->storage->list('fruit', ['name' => 'Apple']);

        // Assertions to verify the filtered results
        $this->assertCount(2, $fruits);
        $this->assertSame('Apple', $fruits[0]->name);
        $this->assertSame(150, $fruits[0]->weight);
    }
}
