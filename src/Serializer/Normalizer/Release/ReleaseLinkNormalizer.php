<?php

namespace App\Serializer\Normalizer\Release;

use App\Entity\Release\ReleaseLink;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ReleaseLinkNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer
    ) {
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        $data['name'] = $data['release_link_name']['link_name'];
        unset($data['release_link_name']);

        return $data;
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
