<?php

namespace App\Collection;

use App\DTO\FruitDTO;
use App\Storage\DatabaseStorage;

class FruitCollection implements CollectionInterface
{
    private array $fruits;

    public function __construct(private DatabaseStorage $storage)
    {
        $this->fruits = $this->storage->list('fruit');    
    }
    
    public function add(string $item, int $grams): void
    {
        $this->storage->add('fruit', ['name' => $item, 'weight' => $grams]);

        $this->fruits[] = new FruitDTO($item, $grams);
    }

    public function remove(string $item): void
    {
        $this->storage->remove('fruit', $item);
        
        $this->fruits = array_filter($this->fruits, fn(FruitDTO $fruit) => $fruit->name !== $item);
    }

    public function list(): array
    {
        return $this->fruits;
    }
}
