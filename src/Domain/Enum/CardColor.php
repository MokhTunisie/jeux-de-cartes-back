<?php

namespace App\Domain\Enum;

enum CardColor: string
{
    case Diamonds = 'Carreaux';
    case Hearts = 'Coeur';
    case Spades = 'Pique';
    case Clubs = 'Trefle';

    /**
     * @return string[]
     */
    public static function getColors(): array
    {
        return array_column(self::cases(), 'value');
    }
}