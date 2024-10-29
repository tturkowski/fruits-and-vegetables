<?php

namespace App\Storage;

interface StorageInterface
{
    public function add(string $collection, array $item): void;
    public function remove(string $collection, string $name): void;
    public function list(string $collection, array $filters = []): array;
}
