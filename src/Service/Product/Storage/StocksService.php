<?php

declare(strict_types=1);

namespace App\Service\Product\Storage;

use App\Enum\FoodTypes;
use App\Service\Product\Creator\Creator;
use App\Service\Product\Storage\Collection\CollectionCreator;
use App\Service\Product\Storage\Exception\MalformedProductsJson;
use App\Util\ProductHydrator\Exception\WrongProductData;
use App\Util\ProductHydrator\NaiveHydrator;
use JsonException;

final class StocksService
{

    public function __construct(
        private readonly Creator $productCreator,
        private readonly CollectionCreator $collectionCreator,
        private readonly NaiveHydrator $productHydrator,
    ) {
    }

    /**
     * @throws MalformedProductsJson
     * @throws WrongProductData
     */
    public function process(string $requestJson): StocksVO
    {
        try {
            $rawItems = json_decode($requestJson, true, 3, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            throw new MalformedProductsJson("Cannot decode provided JSON request");
        }

        $fruits = $this->collectionCreator->createFruitsCollection();
        $vegetables = $this->collectionCreator->createVegetablesCollection();

        // TODO consider using symfony/serializer or JMS instead of NaiveHydrator
        foreach ($rawItems as $item) {
            $productDto = $this->productHydrator->hydrateProduct($item);
            $product = $this->productCreator->createProduct($productDto);

            match ($product->getType()) {
                FoodTypes::FRUIT => $fruits->add($product),
                FoodTypes::VEGETABLE => $vegetables->add($product),
            };
        }
        return new StocksVO($fruits, $vegetables);
    }
}
