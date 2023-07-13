<?php

declare(strict_types=1);

namespace App\Util\ProductHydrator;

use App\Dto\Product;
use App\Enum\FoodTypes;
use App\Enum\MassMeasuresUnits;
use App\Util\ProductHydrator\Exception\WrongProductData;

class NaiveHydrator
{
    /**
     * @throws WrongProductData
     */
    public function hydrateProduct(array $item): Product
    {
        $id = $item['id'] ?? null;
        $name = $item['name'] ?? null;
        $quantity = $item['quantity'] ?? null;
        $type = FoodTypes::tryFrom((string)($item['type'] ?? null));
        $unit = MassMeasuresUnits::tryFrom((string)($item['unit'] ?? null));

        if (!isset($id, $name, $quantity, $type, $unit) || (string)$name === '') {
            throw new WrongProductData("Wrong product item definition in JSON request. id: $id; name: $name");
        }

        return new Product(
            (int)$id,
            (string)$name,
            (int)$quantity,
            $type,
            $unit,
        );
    }
}
