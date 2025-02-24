<?php

namespace App\Domain\Exception;

class MissingCardAttributeException extends DomainException
{
    /**
     * @var int
     */
    protected $code = 400;

    /**
     * @var string
     */
    protected $message = 'Missing card attribute: color or value is not set.';
}
