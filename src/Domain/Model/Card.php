<?php

namespace App\Domain\Model;

final readonly class Card
{
    public function __construct(public string $color, public string $value)
    {
    }
}