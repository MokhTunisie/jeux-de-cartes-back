<?php

namespace App\Domain\Service;

use App\Domain\Model\Palm;

interface PalmSorterInterface
{
    public function sortPalm(Palm $palm): Palm;
}