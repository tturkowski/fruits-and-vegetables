<?php

namespace App\Service;

use App\Collection\FruitCollection;
use App\Collection\VegetableCollection;

class CollectionService
{
    public function __construct(
        private FruitCollection $fruitCollection,
        private VegetableCollection $vegetableCollection
    ) {}

    public function getCollection(string $type)
    {
        $collection = $type === 'fruit' ? $this->fruitCollection : $this->vegetableCollection;
        
        return $collection->list();
    }

    public function addToCollection(string $type, string $name, int $weight): void
    {
        $collection = $type === 'fruit' ? $this->fruitCollection : $this->vegetableCollection;
        $collection->add($name, $weight);
    }

    public function removeFromCollection(string $type, string $name): bool
    {
        $collection = $type === 'fruit' ? $this->fruitCollection : $this->vegetableCollection;
        
        // Check if the item exists to delete and handle accordingly
        if ($collection->list()) { 
            $collection->remove($name);
            return true; 
        }

        return false;
    }
}
