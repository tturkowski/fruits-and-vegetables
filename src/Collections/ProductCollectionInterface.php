<?php
declare(strict_types=1);

namespace App\Collections;

use App\Model\Product;
use Iterator;

interface ProductCollectionInterface
{
    public function add(Product $product): self;

    public function list(): Iterator;

    public function remove(int $id): self;

    public function search(string $needle, string $variable): array;
}