<?php

namespace App\ValueResolver\Release;

use App\Entity\Release\Release;
use App\Exception\Release\ReleaseNotFoundException;
use App\Repository\Release\ReleaseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

#[AsTargetedValueResolver('release')]
readonly class ReleaseValueResolver implements ValueResolverInterface
{
    public function __construct(private ReleaseRepository $releaseRepository)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();
        if (!$argumentType || $argumentType !== Release::class) {
            return [];
        }

        $value = $request->attributes->get($argument->getName());
        if (!\is_string($value)) {
            return [];
        }

        $release = $this->releaseRepository->findOneBy(['slug' => $value]);
        if (!$release) {
            throw new ReleaseNotFoundException();
        }

        return [$release];
    }
}
