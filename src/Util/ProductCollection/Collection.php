<?php

declare(strict_types=1);

namespace App\Util\ProductCollection;

use App\Entity\ProductInterface;
use ArrayIterator;

final class Collection implements CollectionInterface
{

    /**
     * @var ProductInterface[]
     */
    private array $productsCollection = [];

    /**
     * @inheritDoc
     */
    public function add(ProductInterface $product): void
    {
        $this->productsCollection[] = $product;
    }

    public function remove(int $id): int
    {
        $newProductsCollection = array_filter(
            $this->productsCollection,
            static fn(ProductInterface $item) => $item->getId() !== $id,
        );

        $deletedCount = count($this->productsCollection) - count($newProductsCollection);
        $this->productsCollection = $newProductsCollection;
        return $deletedCount;
    }

    /**
     * @inheritDoc
     */
    public function list(): ArrayIterator
    {
        return new ArrayIterator($this->productsCollection);
    }
}
