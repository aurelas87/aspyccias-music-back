<?php

namespace App\Helper;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Throwable;

class ValidationErrorsParser
{
    public function reduceViolations(ConstraintViolationList $violations): array {
        $reducedViolations = [];

        foreach ($violations as $violation) {
            $words = preg_split('/(?=[A-Z])/', $violation->getPropertyPath(), -1, PREG_SPLIT_NO_EMPTY);
            $jsonPropertyPath = strtolower(implode('_', $words));

            $reducedViolations[$jsonPropertyPath] = $violation->getMessage();
        }

        return $reducedViolations;
    }

    public function getValidationErrors(Throwable $throwable): ?array
    {
        $throwableToCheck = $throwable;

        if ($throwableToCheck instanceof UnprocessableEntityHttpException) {
            $throwableToCheck = $throwable->getPrevious();
        }

        if ($throwableToCheck instanceof ValidationFailedException) {
            return $this->reduceViolations($throwableToCheck->getViolations());
        }

        return null;
    }
}
