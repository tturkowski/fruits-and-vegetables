<?php

namespace App\Service;

use App\Collection\FruitCollection;
use App\Collection\VegetableCollection;
use App\Enum\ProduceEnum;

class CollectionService
{
    public function __construct(
        private FruitCollection $fruitCollection,
        private VegetableCollection $vegetableCollection
    ) {}

    public function getCollection(ProduceEnum $type, array $filters = []): array
    {
        $collection = $type === ProduceEnum::FRUIT ? $this->fruitCollection : $this->vegetableCollection;
        return $collection->list($filters);
    }

    public function addToCollection(ProduceEnum $type, string $name, int $weight): void
    {
        $collection = $type === ProduceEnum::FRUIT ? $this->fruitCollection : $this->vegetableCollection;
        $collection->add($name, $weight);
    }

    public function removeFromCollection(ProduceEnum $type, string $name): bool
    {
        $collection = $type === ProduceEnum::FRUIT ? $this->fruitCollection : $this->vegetableCollection;
        
        if ($collection->list()) { 
            $collection->remove($name);
            return true; 
        }

        return false;
    }
}
