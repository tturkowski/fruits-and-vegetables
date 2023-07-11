<?php
declare(strict_types=1);

namespace App\Model;

final class Product
{
    protected int $id;
    protected string $name;
    protected string $type;
    protected float $quantity;
    protected string $unit;

    public const TYPE_VEGETABLE = 'vegetable';
    public const TYPE_FRUIT = 'fruit';

    public const UNIT_G = 'g';
    public const UNIT_KG = 'kg';

    public const SEARCH_BY_ID = 'id';
    public const SEARCH_BY_NAME = 'name';
    public const SEARCH_BY_TYPE = 'type';
    public const SEARCH_BY_QUANTITY = 'quantity';
    public const SEARCH_BY = [
        self::SEARCH_BY_ID,
        self::SEARCH_BY_NAME,
        self::SEARCH_BY_TYPE,
        self::SEARCH_BY_QUANTITY
    ];

    public const UNITS = [self::UNIT_G, self::UNIT_KG];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function setQuantity(int|float $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): self
    {
        $this->unit = $unit;
        return $this;
    }
}