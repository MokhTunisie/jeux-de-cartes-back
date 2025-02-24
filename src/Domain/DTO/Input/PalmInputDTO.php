<?php

namespace App\Domain\DTO\Input;

class PalmInputDTO
{
    /**
     * @param array<CardInputDTO> $cards
     */
    public function __construct(public array $cards)
    {
    }
}