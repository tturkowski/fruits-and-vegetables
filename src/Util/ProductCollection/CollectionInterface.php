<?php

declare(strict_types=1);

namespace App\Util\ProductCollection;

use App\Entity\ProductInterface;
use App\Util\ProductCollection\Exception\CannotAddElement;
use ArrayIterator;

interface CollectionInterface
{

    /**
     * @throws CannotAddElement
     */
    public function add(ProductInterface $product): void;

    /**
     * @return int Count of deleted items
     */
    public function remove(int $id): int;

    /**
     * @return ProductInterface[]|ArrayIterator
     */
    public function list(): ArrayIterator;

}
