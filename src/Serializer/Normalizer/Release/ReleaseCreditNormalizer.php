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

        if (!\array_key_exists('release_credit_type', $data)) {
            throw new \LogicException('The Release Credit data must have a Release Credit Type.');
        }

        if (!\array_key_exists('translations', $data['release_credit_type'])
            || \count($data['release_credit_type']['translations']) !== 1
        ) {
            throw new \LogicException('The Release Credit Type data must have at least one translation.');
        }

        $data['type'] = $data['release_credit_type']['translations'][0]['credit_name'];
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
