<?php

declare(strict_types=1);

namespace App\Service\Product\Storage\Collection;

use App\Enum\FoodTypes;
use App\Util\ProductCollection\Collection;

final class CollectionCreator
{

    public function createFruitsCollection(): TypedProductsCollection
    {
        return new TypedProductsCollection(new Collection(), FoodTypes::FRUIT);
    }

    public function createVegetablesCollection(): TypedProductsCollection
    {
        return new TypedProductsCollection(new Collection(), FoodTypes::VEGETABLE);
    }
}
