<?php
declare(strict_types=1);

namespace App\Filter\Product;

use FilterIterator;
use Iterator;

class TypeFilter extends FilterIterator
{
    public function __construct(Iterator $iterator, protected string $needle)
    {
        parent::__construct($iterator);
    }

    public function accept(): bool
    {
        if ((string)$this->getInnerIterator()->current()->getType() === $this->needle) {
            return true;
        }

        return false;
    }

}