<?php

namespace App\Serializer\Normalizer\Release;

use App\Model\Release\ReleaseLinkCategory;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ReleaseLinkCategoryNormalizer implements NormalizerInterface
{
    public function normalize($object, ?string $format = null, array $context = []): string
    {
        return $object->name;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof ReleaseLinkCategory;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [ReleaseLinkCategory::class => true];
    }
}
