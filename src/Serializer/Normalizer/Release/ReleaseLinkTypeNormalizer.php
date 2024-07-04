<?php

namespace App\Serializer\Normalizer\Release;

use App\Model\Release\ReleaseLinkType;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ReleaseLinkTypeNormalizer implements NormalizerInterface
{
    public function normalize($object, ?string $format = null, array $context = []): string
    {
        return $object->name;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof ReleaseLinkType;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [ReleaseLinkType::class => true];
    }
}
