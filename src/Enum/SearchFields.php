<?php

declare(strict_types=1);

namespace App\Enum;

use App\Entity\ProductInterface;

enum SearchFields: string
{
    case ID = 'id';

    case NAME = 'name';

    case QUANTITY = 'quantity';

    public function getFilteringCallback(array $params): callable
    {
        return match ($this) {
            self::ID => (static fn(ProductInterface $item): bool => $item->getId() === ($params['id'] ?? null)),
            self::NAME => (static fn(ProductInterface $item): bool => $item->getName() === ($params['name'] ?? '')),
            self::QUANTITY => (
            static fn(ProductInterface $item): bool => ($item->getQuantity() >= ($params['min'] ?? PHP_INT_MIN)
                && $item->getQuantity() <= ($params['max'] ?? PHP_INT_MAX))
            ),
        };
    }
}
