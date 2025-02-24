<?php

namespace App\Domain\DTO\Output;

class CardOutputDTO
{
    public function __construct(public string $color, public string $value)
    {
    }
}