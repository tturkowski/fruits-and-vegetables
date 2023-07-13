<?php

declare(strict_types=1);

namespace App\Tests\App\Service\Product\Storage;

use App\Service\Product\Creator\Creator;
use App\Service\Product\Storage\Collection\CollectionCreator;
use App\Service\Product\Storage\Exception\MalformedProductsJson;
use App\Service\Product\Storage\StocksService;
use App\Util\ProductHydrator\NaiveHydrator;
use PHPUnit\Framework\TestCase;

final class StockServiceTest extends TestCase
{

    private const MALFORMED_JSON = 'This request should cause exception';

    /**
     * @dataProvider requestProvider
     */
    public function testProcess(string $request, int $fruitsCount, int $vegetablesCount): void
    {
        $stocksService = new StocksService(
            new Creator(),
            new CollectionCreator(),
            new NaiveHydrator(),
        );

        if ($request === self::MALFORMED_JSON) {
            $this->expectExceptionObject(new MalformedProductsJson("Cannot decode provided JSON request"));
        }

        $stocks = $stocksService->process($request);

        $this->assertCount($fruitsCount, $stocks->getFruits()->list());
        $this->assertCount($vegetablesCount, $stocks->getVegetables()->list());
    }

    public function requestProvider(): array
    {
        return [
            [
                'request' => '[]',
                'fruitsCount' => 0,
                'vegetablesCount' => 0,
            ],
            [
                'request' => <<<JSON
[
  {
    "id": 1,
    "name": "Carrot",
    "type": "vegetable",
    "quantity": 10922,
    "unit": "g"
  },
  {
    "id": 2,
    "name": "Apples",
    "type": "fruit",
    "quantity": 20,
    "unit": "kg"
  },
  {
    "id": 3,
    "name": "Pears",
    "type": "fruit",
    "quantity": 3500,
    "unit": "g"
  },
  {
    "id": 4,
    "name": "Melons",
    "type": "fruit",
    "quantity": 120,
    "unit": "kg"
  }
]
JSON,
                'fruitsCount' => 3,
                'vegetablesCount' => 1,

            ],
            [
                'request' => self::MALFORMED_JSON,
                'fruitsCount' => 0,
                'vegetablesCount' => 0,
            ],
        ];
    }
}
