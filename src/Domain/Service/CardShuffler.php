<?php

namespace App\Domain\Service;

use App\Domain\Exception\PalmCardsCountException;
use App\Domain\Model\Card;
use App\Domain\Enum\CardColor;
use App\Domain\Enum\CardValue;
use App\Domain\Model\Palm;

class CardShuffler implements CardShufflerInterface
{
    /**
     * @throws PalmCardsCountException
     */
    public function shuffleCards(): Palm
    {
        $colors = CardColor::cases();
        $values = CardValue::cases();
        shuffle($colors);
        shuffle($values);

        $cards = [];

        while (count($cards) < 10) {
            $newCard = new Card($colors[array_rand($colors)]->value, $values[array_rand($values)]->value);

            if (!in_array($newCard, $cards)) {
                $cards[] = $newCard;
            }
        }

        return new Palm($cards);
    }
}