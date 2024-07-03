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

        $data['type'] = $data['release_credit_type']['credit_name'];
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
