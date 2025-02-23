<?php

namespace App\Domain\Service;

use App\Domain\Model\Card;
use App\Domain\Model\Palm;

interface CardShufflerInterface
{
    public function shuffleCards(): Palm;
}