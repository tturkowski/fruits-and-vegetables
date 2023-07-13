<?php

declare(strict_types=1);

namespace App\Util\ProductCollection;

use App\Entity\ProductInterface;
use App\Enum\SearchFields;

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
            static fn(ProductInterface $item): bool => $item->getId() !== $id,
        );

        $deletedCount = count($this->productsCollection) - count($newProductsCollection);
        $this->productsCollection = $newProductsCollection;
        return $deletedCount;
    }

    /**
     * @inheritDoc
     */
    public function list(): array
    {
        return array_values($this->productsCollection);
    }

    /**
     * @inheritDoc
     */
    public function search(SearchFields $field, array $params): array
    {
        return array_values(
            array_filter(
                array_values($this->productsCollection),
                $field->getCallbackFilter($params)
            )
        );
    }
}
