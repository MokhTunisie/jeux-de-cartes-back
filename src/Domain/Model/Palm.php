<?php

namespace App\Domain\Model;

use App\Domain\Exception\PalmCardsCountException;

final readonly class Palm
{

    /**
     * @param Card[] $cards
     * @throws PalmCardsCountException
     */
    public function __construct(public array $cards)
    {
        if (count($cards) !== 10) {
            throw new PalmCardsCountException('A palm should consist of exactly 10 cards.');
        }
    }
}