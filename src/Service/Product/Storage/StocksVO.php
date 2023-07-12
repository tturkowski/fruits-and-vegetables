<?php

declare(strict_types=1);

namespace App\Service\Product\Storage;

use App\Service\Product\Storage\Collection\TypedProductsCollection;

final class StocksVO
{
    public function __construct(
        private readonly TypedProductsCollection $fruits,
        private readonly TypedProductsCollection $vegetables,
    ) {
    }

    public function getFruits(): TypedProductsCollection
    {
        return $this->fruits;
    }

    public function getVegetables(): TypedProductsCollection
    {
        return $this->vegetables;
    }

}
