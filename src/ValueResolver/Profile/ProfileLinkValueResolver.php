<?php

namespace App\ValueResolver\Profile;

use App\Entity\Profile\ProfileLink;
use App\Exception\Profile\ProfileLinkNotFoundException;
use App\Repository\Profile\ProfileLinkRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

#[AsTargetedValueResolver('profile_link')]
readonly class ProfileLinkValueResolver implements ValueResolverInterface
{
    public function __construct(private ProfileLinkRepository $profileLinkRepository)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();
        if (!$argumentType || $argumentType !== ProfileLink::class) {
            return [];
        }

        $value = $request->attributes->get($argument->getName());
        if (!\is_string($value)) {
            return [];
        }

        $profileLink = $this->profileLinkRepository->findOneBy(['name' => $value]);
        if (!$profileLink) {
            throw new ProfileLinkNotFoundException();
        }

        return [$profileLink];
    }
}
