<?php

declare(strict_types=1);

namespace App\Service\Product\Storage\Collection;

use App\Entity\ProductInterface;
use App\Enum\FoodTypes;
use App\Enum\SearchFields;
use App\Service\Product\Storage\Exception\WrongProductType;
use App\Util\ProductCollection\CollectionInterface;

final class TypedProductsCollection implements CollectionInterface
{
    public function __construct(
        private readonly CollectionInterface $productsCollection,
        private readonly FoodTypes $type,
    ) {
    }

    /**
     * @throws WrongProductType
     */
    public function add(ProductInterface $product): void
    {
        $this->checkType($product);
        $this->productsCollection->add($product);
    }

    public function remove(int $id): int
    {
        return $this->productsCollection->remove($id);
    }

    /**
     * @inheritDoc
     */
    public function list(): array
    {
        return $this->productsCollection->list();
    }

    /**
     * @inheritDoc
     */
    public function search(SearchFields $field, array $params): array
    {
        return $this->productsCollection->search($field, $params);
    }

    /**
     * @throws WrongProductType
     */
    private function checkType(ProductInterface $product): void
    {
        if ($product->getType() !== $this->type) {
            throw new WrongProductType('Collection accepts only items with type: ' . $this->type->value);
        }
    }
}
