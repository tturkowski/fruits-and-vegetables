<?php

namespace App\Collection;

use App\DTO\VegetableDTO;
use App\Enum\ProduceEnum;
use App\Storage\DatabaseStorage;

class VegetableCollection implements CollectionInterface
{
    private array $vegetables;

    public function __construct(private DatabaseStorage $storage)
    {
        $this->vegetables = $this->storage->list(ProduceEnum::VEGETABLE->value);    
    }
    
    public function add(string $item, int $grams): void
    {
        $this->storage->add(ProduceEnum::VEGETABLE->value, ['name' => $item, 'weight' => $grams]);

        $this->vegetables[] = new VegetableDTO($item, $grams);
    }

    public function remove(string $item): void
    {
        $this->storage->remove(ProduceEnum::VEGETABLE->value, $item);
        
        $this->vegetables = array_filter($this->vegetables, fn(VegetableDTO $vegetableDTO) => $vegetableDTO->name !== $item);
    }

    public function list(array $filters = []): array
    {
        return $this->vegetables;
    }
}
