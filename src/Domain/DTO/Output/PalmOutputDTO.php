<?php

namespace App\Domain\DTO\Output;

class PalmOutputDTO
{
    /**
     * @param CardOutputDTO[] $cards
     */
    public function __construct(public array $cards)
    {
    }
}