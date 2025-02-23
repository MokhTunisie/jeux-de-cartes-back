<?php

namespace App\Domain\Service;

use App\Domain\Exception\PalmCardsCountException;
use App\Domain\Model\Card;
use App\Domain\Model\Palm;
use App\Domain\Enum\CardColor;
use App\Domain\Enum\CardValue;

class PalmSorter implements PalmSorterInterface
{
    /**
     * @throws PalmCardsCountException
     */
    public function sortPalm(Palm $palm): Palm
    {
        $cards = $palm->cards;
        usort($cards, function (Card $card1, Card $card2) {
            $colorOrder = CardColor::getColors();
            $valueOrder = CardValue::getValues();

            $colorComparison = array_search($card1->color, $colorOrder) <=> array_search($card2->color, $colorOrder);
            return $colorComparison === 0 ? array_search($card1->value, $valueOrder) <=> array_search($card2->value, $valueOrder) : $colorComparison;
        });

        return new Palm($cards);
    }
}