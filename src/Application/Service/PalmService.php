<?php

namespace App\Application\Service;

use App\Domain\Model\Palm;
use App\Domain\Service\CardShufflerInterface;
use App\Domain\Service\PalmSorterInterface;
use App\Domain\Service\PalmConverterInterface;

class PalmService implements PalmServiceInterface
{
    public function __construct(
        private readonly CardShufflerInterface $cardShuffler,
        private readonly PalmSorterInterface $palmSorter,
        private readonly PalmConverterInterface $palmConverter
    ) {
    }

    public function generatePalm(): Palm
    {
        return $this->cardShuffler->shuffleCards();
    }

    public function sortPalm(Palm $palm): Palm
    {
        return $this->palmSorter->sortPalm($palm);
    }

    public function convertToPalm(array $cards): Palm
    {
        return $this->palmConverter->convertToPalm($cards);
    }
}