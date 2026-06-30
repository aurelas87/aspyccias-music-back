<?php

namespace App\Serializer\Normalizer\Release;

use App\Model\Release\ReleaseLinkCategory;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ReleaseLinkCategoryNormalizer implements NormalizerInterface
{
    public function normalize($data, ?string $format = null, array $context = []): string
    {
        return $data->name;
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
