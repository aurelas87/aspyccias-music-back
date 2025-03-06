<?php

namespace App\Serializer\Normalizer\Release;

use App\Entity\Release\ReleaseCredit;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ReleaseCreditNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer
    ) {
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        $isAdmin = \in_array('admin', $context['groups'], true);
        $isAdminCredits = \in_array('admin-credits', $context['groups'], true);

        if (!\array_key_exists('release_credit_type', $data)) {
            throw new \LogicException('The Release Credit data must have a Release Credit Type.');
        }

        if (!\array_key_exists('translations', $data['release_credit_type'])) {
            if (!$isAdmin && !$isAdminCredits && !\array_key_exists('credit_name', $data['release_credit_type'])) {
                throw new \LogicException('The Release Credit Type data must have a credit name.');
            }

            if ($isAdmin) {
                if (!\array_key_exists('credit_name_fr', $data['release_credit_type'])) {
                    throw new \LogicException('The Release Credit Type data must have a "fr" credit name.');
                }

                if (!\array_key_exists('credit_name_en', $data['release_credit_type'])) {
                    throw new \LogicException('The Release Credit Type data must have a "en" credit name.');
                }
            }

            if ($isAdminCredits && !\array_key_exists('credit_name_key', $data['release_credit_type'])) {
                throw new \LogicException('The Release Credit Type data must have credit name key.');
            }
        }

        if (!$isAdmin && !$isAdminCredits) {
            $data['type'] = $data['release_credit_type']['credit_name'];
        } elseif ($isAdmin) {
            $data['type'] = $data['release_credit_type']['credit_name_en'];
        } else {
            $data['type'] = $data['release_credit_type']['credit_name_key'];
        }

        unset($data['release_credit_type']);

        return $data;
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
