<?php

namespace App\Application\Service;

use App\Domain\DTO\Input\CardInputDTO;
use App\Domain\DTO\Input\PalmInputDTO;
use App\Domain\Model\Palm;

interface PalmServiceInterface
{
    public function generatePalm(): Palm;
    public function sortPalm(Palm $palm): Palm;

    /**
     * @param array<CardInputDTO> $cards
     */
    public function convertToPalm(array $cards): Palm;

    /**
     * @return array<string>
     */
    public function validateAndProcessPalmInput(PalmInputDTO $palmInputDTO): array;
}