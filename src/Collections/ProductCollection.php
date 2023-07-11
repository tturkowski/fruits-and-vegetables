<?php
declare(strict_types=1);

namespace App\Collections;

use App\Filter\Product\IdFilter;
use App\Filter\Product\NameFilter;
use App\Filter\Product\QuantityFilter;
use App\Filter\Product\TypeFilter;
use App\Model\Product;
use ArrayIterator;
use Exception;
use Iterator;

abstract class ProductCollection implements ProductCollectionInterface
{
    private array $products;

    public function add(Product $product): self
    {
        $this->products[] = $product;
        return $this;
    }

    public function list(): Iterator
    {
        return new ArrayIterator($this->products);
    }

    public function remove(int $id): self
    {
        /** @var Product $product */
        foreach ($this->products as $key => $product) {
            if ($product->getId() === $id) {
                unset($this->products[$key]);
            }
        }
        return $this;
    }

    /**
     * @throws Exception
     */
    public function search(string $needle, string $variable): array
    {
        if (!in_array($variable, Product::SEARCH_BY)) {
            throw new Exception('sdfsdf');
        }

        $filter = match ($variable) {
            Product::SEARCH_BY_ID => new IdFilter($this->list(), $needle),
            Product::SEARCH_BY_NAME => new NameFilter($this->list(), $needle),
            Product::SEARCH_BY_TYPE => new TypeFilter($this->list(), $needle),
            Product::SEARCH_BY_QUANTITY => new QuantityFilter($this->list(), $needle),
        };

        $result = [];
        foreach ($filter as $item) {
            $result[] = $item;
        }
        return $result;
    }
}