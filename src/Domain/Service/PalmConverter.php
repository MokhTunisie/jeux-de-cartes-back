<?php

namespace App\Domain\Service;

use App\Domain\Enum\CardColor;
use App\Domain\Enum\CardValue;
use App\Domain\Exception\PalmCardsCountException;
use App\Domain\Exception\InvalidCardAttributeException;
use App\Domain\Model\Card;
use App\Domain\Model\Palm;

class PalmConverter implements PalmConverterInterface
{
    /**
     * @throws PalmCardsCountException
     * @throws InvalidCardAttributeException
     */
    public function convertToPalm(array $cards): Palm
    {
        $cardObjects = [];
        foreach ($cards as $card) {
            if (!in_array($card->color, CardColor::getColors()) || !in_array($card->value, CardValue::getValues())) {
                throw new InvalidCardAttributeException();
            }

            $cardObjects[] = new Card(
                $card->color,
                $card->value
            );
        }
        return new Palm($cardObjects);
    }
}