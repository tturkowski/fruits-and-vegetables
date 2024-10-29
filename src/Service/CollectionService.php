<?php

namespace App\Service;

use App\Collection\FruitCollection;
use App\Collection\VegetableCollection;
use App\Enum\ProduceEnum;
use InvalidArgumentException;

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

    public function processJsonRequest(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new InvalidArgumentException("File not found: {$filePath}");
        }

        $data = json_decode(file_get_contents($filePath), true);

        foreach ($data as $item) {
            if (!isset($item['name'], $item['type'], $item['quantity'], $item['unit'])) {
                throw new InvalidArgumentException("Invalid item format in JSON.");
            }

            $quantityInGrams = $item['unit'] === 'kg' ? $item['quantity'] * 1000 : $item['quantity'];
            $produceType = $item['type'] === 'fruit' ? ProduceEnum::FRUIT : ProduceEnum::VEGETABLE;

            $this->addToCollection($produceType, $item['name'], $quantityInGrams);
        }
    }
}
