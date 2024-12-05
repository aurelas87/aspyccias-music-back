<?php

namespace App\Validator\Release;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ReleaseCreditType extends Constraint
{
    public string $message = '"{{ string }} " is not a valid credit type.';
}
