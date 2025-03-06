<?php

namespace App\Serializer\Normalizer\Release;

use App\Entity\Release\ReleaseCreditType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ReleaseCreditTypeNormalizer implements NormalizerInterface
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

        if ($isAdminCredits) {
            return $data;
        }

        if (!\array_key_exists('translations', $data) || (!$isAdmin && \count($data['translations']) !== 1)) {
            throw new \LogicException('The Release Credit Type data must have at least one translation.');
        }

        if (!$isAdmin) {
            $data['credit_name'] = $data['translations'][0]['credit_name'];
        } else {
            foreach ($data['translations'] as $translation) {
                $data['credit_name_'.$translation['locale']] = $translation['credit_name'];
            }
        }

        unset($data['translations']);

        return $data;
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
