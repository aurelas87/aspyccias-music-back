<?php

namespace App\Tests\Helper;

use App\Helper\ValidationErrorsParser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ValidationErrorsParserTest extends TestCase
{
    private const EXPECTED_REDUCED_VIOLATIONS = [
        'property_path' => 'An error',
        'other_property_path' => 'Another error',
    ];

    private ValidationErrorsParser $validationErrorsParser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validationErrorsParser = new ValidationErrorsParser();
    }

    private function createConstraintViolationList(): ConstraintViolationList
    {
        return new ConstraintViolationList([
            new ConstraintViolation('An error', null, [], null, 'propertyPath', null),
            new ConstraintViolation('Another error', null, [], null, 'otherPropertyPath', null),
        ]);
    }

    public function testReduceValidationErrors(): void
    {
        $reducedViolations = $this->validationErrorsParser->reduceViolations($this->createConstraintViolationList());

        static::assertCount(2, $reducedViolations);
        static::assertSame(self::EXPECTED_REDUCED_VIOLATIONS, $reducedViolations);
    }

    public function dataProviderGetValidationErrors(): array
    {
        return [
            'From main exception' => ['previous' => false],
            'From previous exception' => ['previous' => true],
        ];
    }

    /**
     * @dataProvider dataProviderGetValidationErrors
     */
    public function testGetValidationErrors(bool $previous): void
    {
        $mainException = new ValidationFailedException(null, $this->createConstraintViolationList());
        if ($previous) {
            $mainException = new UnprocessableEntityHttpException('Unprocessable entity', $mainException);
        }

        $validationErrors = $this->validationErrorsParser->getValidationErrors($mainException);

        static::assertCount(2, $validationErrors);
        static::assertSame(self::EXPECTED_REDUCED_VIOLATIONS, $validationErrors);
    }
}
