<?php

namespace App\Application\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class EnumChoice extends Constraint
{
    public string $message = 'Invalid {{ field }}: {{ value }}. Possible values: {{ choices }}.';
    public string $field;
    /**
     * @var string[]
     */
    public array $choices;

    /**
     * @return string[]
     */
    public function getRequiredOptions(): array
    {
        return ['field', 'choices'];
    }

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}