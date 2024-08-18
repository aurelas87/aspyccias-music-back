<?php

namespace App\ValueResolver\Release;

use App\Model\Release\ReleaseType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

#[AsTargetedValueResolver('release_type')]
class ReleaseTypeValueResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();
        if (!$argumentType || $argumentType !== ReleaseType::class) {
            return [];
        }

        $value = $request->attributes->get($argument->getName());
        if (!\is_string($value)) {
            return [];
        }

        return [$argumentType::tryFromName($value)];
    }
}
