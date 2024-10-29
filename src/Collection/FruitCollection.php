<?php

namespace App\Collection;

use App\DTO\FruitDTO;
use App\Enum\ProduceEnum;
use App\Storage\DatabaseStorage;

class FruitCollection implements CollectionInterface
{
    private array $fruits;

    public function __construct(private DatabaseStorage $storage)
    {
        $this->fruits = $this->storage->list(ProduceEnum::FRUIT->value);    
    }
    
    public function add(string $item, int $grams): void
    {
        $this->storage->add(ProduceEnum::FRUIT->value, ['name' => $item, 'weight' => $grams]);

        $this->fruits[] = new FruitDTO($item, $grams);
    }

    public function remove(string $item): void
    {
        $this->storage->remove(ProduceEnum::FRUIT->value, $item);
        
        $this->fruits = array_filter($this->fruits, fn(FruitDTO $fruit) => $fruit->name !== $item);
    }

    public function list(array $filters = []): array
    {
        if ($filters) {
            return $this->storage->list(ProduceEnum::FRUIT->value, $filters);
        }

        return $this->fruits;
    }
}
