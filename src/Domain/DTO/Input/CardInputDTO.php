<?php

namespace App\Domain\DTO\Input;

readonly class CardInputDTO
{
    public function __construct(public string $color, public string $value)
    {
    }
}