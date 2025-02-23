<?php

namespace App\Domain\Exception;

class PalmCardsCountException extends DomainException
{

    /**
     * @var int
     */
    protected $code = 400;

    /**
     * @var string
     */
    protected $message = 'Invalid number of cards provided.';
}