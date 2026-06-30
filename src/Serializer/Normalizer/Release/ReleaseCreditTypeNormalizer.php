<?php

namespace App\Serializer\Normalizer\Release;

use App\Entity\Release\ReleaseCreditType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class ReleaseCreditTypeNormalizer implements NormalizerInterface
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

        if ($isAdminCredits) {
            return $normalizedData;
        }

        if (!\array_key_exists('translations', $normalizedData) || (!$isAdmin && \count($normalizedData['translations']) !== 1)) {
            throw new \LogicException('The Release Credit Type data must have at least one translation.');
        }

        if (!$isAdmin) {
            $normalizedData['credit_name'] = $normalizedData['translations'][0]['credit_name'];
        } else {
            foreach ($normalizedData['translations'] as $translation) {
                $normalizedData['credit_name_'.$translation['locale']] = $translation['credit_name'];
            }
        }

        unset($normalizedData['translations']);

        return $normalizedData;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof ReleaseCreditType;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [ReleaseCreditType::class => true];
    }
}
