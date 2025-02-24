<?php

namespace App\Tests\Application\Validator\Constraints;

use App\Application\Validator\Constraints\EnumChoice;
use App\Application\Validator\Constraints\EnumChoiceValidator;
use App\Domain\Enum\CardColor;
use App\Domain\Enum\CardValue;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class EnumChoiceValidatorTest extends TestCase
{
    private EnumChoiceValidator $validator;
    private MockObject $context;

    protected function setUp(): void
    {
        $this->validator = new EnumChoiceValidator();
        $this->context = $this->createMock(ExecutionContextInterface::class);
        $violationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);

        $this->context->method('buildViolation')->willReturn($violationBuilder);
        $this->validator->initialize($this->context);
    }

    public function testValidValue(): void
    {
        $constraint = new EnumChoice([
            'field' => 'value',
            'choices' => ['callback' => [CardValue::class, 'getValues']]
        ]);

        $this->context->expects($this->never())->method('buildViolation');

        $this->validator->validate('AS', $constraint);
    }

    public function testInvalidValue(): void
    {
        $constraint = new EnumChoice([
            'field' => 'value',
            'choices' => ['callback' => [CardValue::class, 'getValues']]
        ]);

        $this->context->expects($this->once())->method('buildViolation')->with($constraint->message);

        $this->validator->validate('InvalidValue', $constraint);
    }

    public function testValidColor(): void
    {
        $constraint = new EnumChoice([
            'field' => 'color',
            'choices' => ['callback' => [CardColor::class, 'getColors']]
        ]);

        $this->context->expects($this->never())->method('buildViolation');

        $this->validator->validate('Coeur', $constraint);
    }

    public function testInvalidColor(): void
    {
        $constraint = new EnumChoice([
            'field' => 'color',
            'choices' => ['callback' => [CardColor::class, 'getColors']]
        ]);

        $this->context->expects($this->once())->method('buildViolation')->with($constraint->message);

        $this->validator->validate('InvalidColor', $constraint);
    }
}