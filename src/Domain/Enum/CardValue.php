<?php

namespace App\Domain\Enum;

enum CardValue: string
{
    case Ace = 'AS';
    case Two = '2';
    case Three = '3';
    case Four = '4';
    case Five = '5';
    case Six = '6';
    case Seven = '7';
    case Eight = '8';
    case Nine = '9';
    case Ten = '10';
    case Jack = 'Valet';
    case Queen = 'Dame';
    case King = 'Roi';

    /**
     * @return string[]
     */
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}