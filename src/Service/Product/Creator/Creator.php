<?php

declare(strict_types=1);

namespace App\Service\Product\Creator;

use App\Dto\Product;
use App\Entity\Fruit;
use App\Entity\ProductInterface;
use App\Entity\Vegetable;
use App\Enum\FoodTypes;
use App\Service\Product\Creator\Exception\UnknownProductType;
use UnhandledMatchError;

final class Creator
{
    /**
     * @throws UnknownProductType
     */
    public function createProduct(Product $productDto): ProductInterface
    {
        try {
            $quantity = $productDto->getQuantity() * $productDto->getUnit()->getMultiplier();

            return match ($productDto->getType()) {
                FoodTypes::FRUIT => new Fruit($productDto->getId(), $productDto->getName(), $quantity),
                FoodTypes::VEGETABLE => new Vegetable($productDto->getId(), $productDto->getName(), $quantity),
            };
        } catch (UnhandledMatchError $e) {
            throw new UnknownProductType('Creator cannot create product with type: ' . $productDto->getType()->value);
        }
    }

}
