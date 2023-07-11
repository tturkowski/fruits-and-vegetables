<?php
declare(strict_types=1);

namespace App\Filter\Product;

use FilterIterator;
use Iterator;

class NameFilter extends FilterIterator
{
    public function __construct(Iterator $iterator, protected string $needle)
    {
        parent::__construct($iterator);
    }

    public function accept(): bool
    {
        if ($this->getInnerIterator()->current()->getName() === $this->needle) {
            return true;
        }

        return false;
    }
}