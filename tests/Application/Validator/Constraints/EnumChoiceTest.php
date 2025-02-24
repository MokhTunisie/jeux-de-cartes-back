<?php

namespace App\Tests\Application\Validator\Constraints;

use App\Application\Validator\Constraints\EnumChoice;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

class EnumChoiceTest extends TestCase
{
    public function testValidEnumChoice(): void
    {
        $constraint = new EnumChoice(['field' => 'status', 'choices' => ['active', 'inactive']]);
        $this->assertEquals(['field', 'choices'], $constraint->getRequiredOptions());
        $this->assertEquals('status', $constraint->field);
        $this->assertEquals(['active', 'inactive'], $constraint->choices);
    }

    public function testInvalidEnumChoiceWithoutField(): void
    {
        $this->expectException(MissingOptionsException::class);
        new EnumChoice(['choices' => ['active', 'inactive']]);
    }

    public function testInvalidEnumChoiceWithoutChoices(): void
    {
        $this->expectException(MissingOptionsException::class);
        new EnumChoice(['field' => 'status']);
    }

    public function testGetTargetsReturnsPropertyConstraint(): void
    {
        $constraint = new EnumChoice(['field' => 'status', 'choices' => ['active', 'inactive']]);
        $this->assertEquals(Constraint::PROPERTY_CONSTRAINT, $constraint->getTargets());
    }
}