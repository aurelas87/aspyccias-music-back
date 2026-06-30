<?php

namespace App\Serializer\Normalizer\Release;

use App\Entity\Release\ReleaseLink;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class ReleaseLinkNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer
    ) {
    }

    public function normalize($data, ?string $format = null, array $context = []): array
    {
        $normalizedData = $this->normalizer->normalize($data, $format, $context);

        $normalizedData['name'] = $normalizedData['release_link_name']['link_name'];
        unset($normalizedData['release_link_name']);

        return $normalizedData;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof ReleaseLink;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [ReleaseLink::class => true];
    }
}
