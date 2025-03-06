<?php

namespace App\Validator\Release;

use App\Entity\Release\ReleaseCreditType as ReleaseCreditTypeEntity;
use App\Repository\Release\ReleaseCreditTypeRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ReleaseCreditTypeValidator extends ConstraintValidator
{
    public function __construct(private readonly ReleaseCreditTypeRepository $releaseCreditTypeRepository)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ReleaseCreditType) {
            throw new UnexpectedTypeException($constraint, ReleaseCreditType::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');
        }

        $releaseCreditType = $this->releaseCreditTypeRepository->findOneBy(['creditNameKey' => $value]);
        if ($releaseCreditType instanceof ReleaseCreditTypeEntity) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $value)
            ->addViolation();
    }
}
