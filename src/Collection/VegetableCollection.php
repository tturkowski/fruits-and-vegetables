<?php

namespace App\Collection;

use App\DTO\VegetableDTO;
use App\Storage\DatabaseStorage;

class VegetableCollection implements CollectionInterface
{
    private array $vegetables;

    public function __construct(private DatabaseStorage $storage)
    {
        $this->vegetables = $this->storage->list('vegetable');    
    }
    
    public function add(string $item, int $grams): void
    {
        $this->storage->add('vegetable', ['name' => $item, 'weight' => $grams]);

        $this->vegetables[] = new VegetableDTO($item, $grams);
    }

    public function remove(string $item): void
    {
        $this->storage->remove('vegetable', $item);
        
        $this->vegetables = array_filter($this->vegetables, fn(VegetableDTO $vegetableDTO) => $vegetableDTO->name !== $item);
    }

    public function list(): array
    {
        return $this->vegetables;
    }
}
