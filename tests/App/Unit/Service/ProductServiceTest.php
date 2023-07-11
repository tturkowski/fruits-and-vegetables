<?php

namespace App\Tests\App\Unit\Service;

use App\Model\Product;
use App\Service\ProductService;
use PHPUnit\Framework\TestCase;

class ProductServiceTest extends TestCase
{
    public function testSavingData(): void
    {
        $fruit = (new Product())
            ->setId(1)
            ->setName('Apple')
            ->setUnit('g')
            ->setType('fruit')
            ->setQuantity(100);

        $vegetable = (new Product())
            ->setId(2)
            ->setName('Pumpkin')
            ->setUnit('kg')
            ->setType('vegetable')
            ->setQuantity(2);

        $productService = new ProductService([$fruit, $vegetable]);
        $productService->saveData();
        $result = $productService->getData(Product::UNIT_G);

        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
    }
}
