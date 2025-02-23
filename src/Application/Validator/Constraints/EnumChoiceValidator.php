<?php

namespace App\Application\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EnumChoiceValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param EnumChoice $constraint
     * @return void
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        /** @var EnumChoice $constraint */
        $choices = is_callable($constraint->choices['callback'] ?? null) ? call_user_func($constraint->choices['callback']) : $constraint->choices;

        if (!in_array($value, $choices, true)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ field }}', $constraint->field)
                ->setParameter('{{ value }}', $value)
                ->setParameter('{{ choices }}', implode(', ', $choices))
                ->addViolation();
        }
    }
}