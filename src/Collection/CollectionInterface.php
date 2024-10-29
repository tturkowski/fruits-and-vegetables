<?php

namespace App\Collection;

interface CollectionInterface
{
    public function add(string $item, int $grams): void;
    public function remove(string $item): void;
    public function list(array $filters = []): array;
}
