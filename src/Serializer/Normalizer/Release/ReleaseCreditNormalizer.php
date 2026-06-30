<?php

namespace App\Serializer\Normalizer\Release;

use App\Entity\Release\ReleaseCredit;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class ReleaseCreditNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer
    ) {
    }

    public function normalize($data, ?string $format = null, array $context = []): array
    {
        $normalizedData = $this->normalizer->normalize($data, $format, $context);
        $isAdmin = \in_array('admin', $context['groups'], true);
        $isAdminCredits = \in_array('admin-credits', $context['groups'], true);

        if (!\array_key_exists('release_credit_type', $normalizedData)) {
            throw new \LogicException('The Release Credit data must have a Release Credit Type.');
        }

        if (!\array_key_exists('translations', $normalizedData['release_credit_type'])) {
            if (!$isAdmin && !$isAdminCredits && !\array_key_exists('credit_name', $normalizedData['release_credit_type'])) {
                throw new \LogicException('The Release Credit Type data must have a credit name.');
            }

            if ($isAdmin) {
                if (!\array_key_exists('credit_name_fr', $normalizedData['release_credit_type'])) {
                    throw new \LogicException('The Release Credit Type data must have a "fr" credit name.');
                }

                if (!\array_key_exists('credit_name_en', $normalizedData['release_credit_type'])) {
                    throw new \LogicException('The Release Credit Type data must have a "en" credit name.');
                }
            }

            if ($isAdminCredits && !\array_key_exists('credit_name_key', $normalizedData['release_credit_type'])) {
                throw new \LogicException('The Release Credit Type data must have credit name key.');
            }
        }

        if (!$isAdmin && !$isAdminCredits) {
            $normalizedData['type'] = $normalizedData['release_credit_type']['credit_name'];
        } elseif ($isAdmin) {
            $normalizedData['type'] = $normalizedData['release_credit_type']['credit_name_en'];
        } else {
            $normalizedData['type'] = $normalizedData['release_credit_type']['credit_name_key'];
        }

        unset($normalizedData['release_credit_type']);

        return $normalizedData;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof ReleaseCredit;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [ReleaseCredit::class => true];
    }
}
