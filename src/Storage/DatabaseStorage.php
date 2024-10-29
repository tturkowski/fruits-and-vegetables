<?php

namespace App\Storage;

use App\DTO\FruitDTO;
use App\Entity\Fruit;
use App\Entity\Vegetable;
use App\Repository\FruitRepository;
use App\Repository\VegetableRepository;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseStorage implements StorageInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function add(string $collection, array $item): void
    {
        $entity = $this->createEntity($collection, $item);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
    
    public function remove(string $collection, string $name): bool
    {
        $repository = $this->getRepository($collection);
        $entity = $repository->findOneBy(['name' => $name]);
    
        if (!$entity) {
            return false;
        }
    
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    
        return true;
    }

    public function list(string $collection, array $filters = []): array
    {   
        $entities = $this->getRepository($collection)->findByFilters($filters);

        return array_map(fn($entity) => new FruitDTO($entity->getName(), $entity->getWeight()), $entities);
    }
    
    private function createEntity(string $collection, array $item): Fruit|Vegetable
    {
        if (!isset($item['name']) || !isset($item['weight'])) {
            throw new \InvalidArgumentException("Missing required fields for item creation: 'name' and 'weight' must be provided.");
        }

        $entity = ($collection === 'fruit') ? new Fruit() : new Vegetable();
        
        $entity->setName($item['name']);
        $entity->setWeight($item['weight']);
        
        return $entity;
    }
    
    private function getRepository(string $collection): VegetableRepository|FruitRepository
    {
        if ($collection === 'fruit') {
            return $this->entityManager->getRepository(Fruit::class);
        } elseif ($collection === 'vegetable') {
            return $this->entityManager->getRepository(Vegetable::class);
        }
        
        throw new \InvalidArgumentException("Unsupported collection type: $collection");
    }
}
