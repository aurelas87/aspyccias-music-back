<?php

namespace App\Serializer\Normalizer\Release;

use App\Entity\Release\Release;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ReleaseNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer
    ) {
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        if (\in_array('details', $context['groups'], true)) {
            if (!\array_key_exists('translations', $data) || \count($data['translations']) !== 1) {
                throw new \LogicException('The Release data must have at least one translation.');
            }

            $data['description'] = $data['translations'][0]['description'];
        }

        unset($data['translations']);

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Release;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Release::class => true];
    }
}
