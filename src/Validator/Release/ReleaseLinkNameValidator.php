<?php

namespace App\Validator\Release;

use App\Entity\Release\ReleaseLinkName as ReleaseLinkNameEntity;
use App\Repository\Release\ReleaseLinkNameRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ReleaseLinkNameValidator extends ConstraintValidator
{
    public function __construct(private readonly ReleaseLinkNameRepository $releaseLinkNameRepository)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ReleaseLinkName) {
            throw new UnexpectedTypeException($constraint, ReleaseLinkName::class);
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

        $releaseLinkName = $this->releaseLinkNameRepository->findOneBy(['linkName' => $value]);
        if ($releaseLinkName instanceof ReleaseLinkNameEntity) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $value)
            ->addViolation();
    }
}
