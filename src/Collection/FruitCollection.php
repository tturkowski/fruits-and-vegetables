<?php

namespace App\Collection;

use App\DTO\ProduceDTO;
use App\Enum\ProduceEnum;
use App\Storage\DatabaseStorage;
use InvalidArgumentException;

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

        $this->fruits[] = new ProduceDTO($item, $grams);
    }

    public function remove(string $item): bool
    {
        try { 
            $this->storage->remove(ProduceEnum::FRUIT->value, $item);
        } catch (InvalidArgumentException $e) {
            return false;
        }
        
        $this->fruits = array_filter($this->fruits, fn(ProduceDTO $fruit) => $fruit->name !== $item);
        
        return true;
    }

    public function list(array $filters = []): array
    {
        if ($filters) {
            return $this->storage->list(ProduceEnum::FRUIT->value, $filters);
        }

        return $this->fruits;
    }

    public function search(string $filter): array
    {
        return $this->list(['name' => $filter]);
    }
}
