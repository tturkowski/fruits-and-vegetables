<?php 

namespace App\DTO;

class ProduceDTO
{
    public function __construct(
        public string $name, 
        public int $weight,
    ) {
    }
}
