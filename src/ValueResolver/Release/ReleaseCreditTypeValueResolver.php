<?php

namespace App\ValueResolver\Release;

use App\Entity\Release\ReleaseCreditType;
use App\Exception\Release\ReleaseCreditTypeNotFoundException;
use App\Repository\Release\ReleaseCreditTypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

#[AsTargetedValueResolver('release_credit_type')]
readonly class ReleaseCreditTypeValueResolver implements ValueResolverInterface
{
    public function __construct(private ReleaseCreditTypeRepository $releaseCreditTypeRepository)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();
        if (!$argumentType || $argumentType !== ReleaseCreditType::class) {
            return [];
        }

        $value = $request->attributes->get($argument->getName());
        if (!\is_string($value)) {
            return [];
        }

        $news = $this->releaseCreditTypeRepository->findOneBy(['creditNameKey' => $value]);
        if (!$news) {
            throw new ReleaseCreditTypeNotFoundException();
        }

        return [$news];
    }
}
