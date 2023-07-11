<?php
declare(strict_types=1);

namespace App\Service;

use App\Collections\FruitsCollection;
use App\Collections\VegetablesCollection;
use App\Model\Product;

class ProductService
{
    private VegetablesCollection $vegetables;
    private FruitsCollection $fruits;

    public function __construct(
        protected array $data,
    )
    {
        $this->vegetables = new VegetablesCollection();
        $this->fruits = new FruitsCollection();
    }

    public function saveData(): self
    {
        foreach ($this->data as $product) {

            if ($product->getUnit() === Product::UNIT_KG) {
                $product->setQuantity($product->getQuantity() * 1000);
                $product->setUnit(Product::UNIT_G);
            }

            if ($product->getType() === Product::TYPE_VEGETABLE) {
                $this->vegetables->add($product);
            }

            if ($product->getType() === Product::TYPE_FRUIT) {
                $this->fruits->add($product);
            }
        }
        return $this;
    }

    public function getData(string $measurement): array
    {
        foreach ($this->vegetables->list() as &$vegetable) {
            $vegetable = $this->changeWeightUnit($vegetable, $measurement);
        }
        foreach ($this->fruits->list() as &$fruit) {
            $fruit = $this->changeWeightUnit($fruit, $measurement);
        }
        return [$this->vegetables->list(), $this->fruits->list()];
    }

    private function changeWeightUnit(Product $product, string $measurement): Product
    {
        if ($product->getUnit() !== $measurement) {
            if ($product->getUnit() === Product::UNIT_KG) {
                $product->setQuantity($product->getQuantity() * 1000);
                $product->setUnit(Product::UNIT_G);
            }
            if ($product->getUnit() === Product::UNIT_G) {
                $product->setQuantity($product->getQuantity() / 1000);
                $product->setUnit(Product::UNIT_KG);
            }
        }
        return $product;
    }
}
