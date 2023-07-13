<?php

declare(strict_types=1);

namespace App\Util\ProductCollection;

use App\Entity\ProductInterface;
use App\Enum\SearchFields;
use App\Util\ProductCollection\Exception\CannotAddElement;

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
     * @return ProductInterface[]
     */
    public function list(): array;

    /**
     * @param array $params Possible keys 'id' => int, 'name' => string, 'min' => int, 'max' => int
     * @examples
     *      search(SearchFields::ID, ['id' => int])
     *      search(SearchFields::NAME, ['name' => string])
     *      search(SearchFields::QUANTITY, ['max' => int])
     *      search(SearchFields::QUANTITY, ['min' => int])
     *      search(SearchFields::QUANTITY, ['min' => int, 'max' => int])
     *
     * @return ProductInterface[]
     */
    public function search(SearchFields $field, array $params): array;

}
