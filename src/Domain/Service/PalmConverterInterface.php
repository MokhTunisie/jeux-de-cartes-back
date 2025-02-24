<?php

namespace App\Domain\Service;

use App\Domain\DTO\Input\CardInputDTO;
use App\Domain\Model\Palm;

interface PalmConverterInterface
{
    /**
     * @param array<CardInputDTO> $cards
     */
    public function convertToPalm(array $cards): Palm;
}