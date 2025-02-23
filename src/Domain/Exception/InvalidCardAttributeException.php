<?php

namespace App\Domain\Exception;

class InvalidCardAttributeException extends DomainException
{
    /**
     * @var int
     */
    protected $code = 400;

    /**
     * @var string
     */
    protected $message = 'Invalid card attribute: color or value is not valid.';
}
