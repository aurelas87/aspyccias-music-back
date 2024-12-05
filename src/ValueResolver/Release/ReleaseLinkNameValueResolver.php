<?php

namespace App\ValueResolver\Release;

use App\Entity\Release\ReleaseLinkName;
use App\Exception\Release\ReleaseLinkNameNotFoundException;
use App\Repository\Release\ReleaseLinkNameRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

#[AsTargetedValueResolver('release_link_name')]
readonly class ReleaseLinkNameValueResolver implements ValueResolverInterface
{
    public function __construct(private ReleaseLinkNameRepository $releaseLinkNameRepository)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();
        if (!$argumentType || $argumentType !== ReleaseLinkName::class) {
            return [];
        }

        $value = $request->attributes->get($argument->getName());
        if (!\is_string($value)) {
            return [];
        }

        $releaseLinkName = $this->releaseLinkNameRepository->findOneBy(['linkName' => $value]);
        if (!$releaseLinkName) {
            throw new ReleaseLinkNameNotFoundException();
        }

        return [$releaseLinkName];
    }
}
