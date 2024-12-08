<?php

namespace App\Validator\Release;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ReleaseLinkName extends Constraint
{
    public string $message = '"{{ string }} " is not a valid link name.';
}
