<?php

namespace App\Service\Release;

use App\Entity\Release\Release;
use App\Exception\Release\InvalidReleaseTypeOptionException;
use App\Exception\Release\MissingReleaseTypeOptionException;
use App\Model\Release\ReleaseType;
use App\Repository\Release\ReleaseRepository;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReleaseService
{
    private ReleaseRepository $releaseRepository;

    public function __construct(ReleaseRepository $releaseRepository)
    {
        $this->releaseRepository = $releaseRepository;
    }

    /**
     * @return Release[]
     */
    public function listReleases(string $locale, array $queryParameters): array
    {
        $resolver = new OptionsResolver();
        $resolver
            ->setRequired('type')
            ->addAllowedTypes('type', 'string')
            ->addNormalizer('type', function (Options $options, string $value) {
                $releaseType = ReleaseType::tryFromName($value);

                if (\is_null($releaseType)) {
                    throw new InvalidReleaseTypeOptionException();
                }

                return $releaseType;
            });

        try {
            $options = $resolver->resolve($queryParameters);
        } catch (MissingOptionsException $e) {
            throw new MissingReleaseTypeOptionException();
        } catch (InvalidOptionsException $e) {
            throw new InvalidReleaseTypeOptionException();
        }

        return $this->releaseRepository->findByTypeLocalized($options['type'], $locale);
    }
}
