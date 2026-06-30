<?php

namespace App\Serializer\Normalizer\Release;

use App\Entity\Release\Release;
use App\Model\Release\ReleaseType;
use LogicException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class ReleaseNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer
    ) {
    }

    public function normalize($data, ?string $format = null, array $context = []): array
    {
        $normalizedData = $this->normalizer->normalize($data, $format, $context);
        $isAdmin = in_array('admin', $context['groups'], true);

        if (in_array('details', $context['groups'], true)) {
            if (!array_key_exists('translations', $normalizedData) || (!$isAdmin && count($normalizedData['translations']) !== 1)) {
                throw new LogicException('The Release data must have at least one translation.');
            }

            if (!$isAdmin) {
                $normalizedData['description'] = $normalizedData['translations'][0]['description'];
            } else {
                foreach ($normalizedData['translations'] as $translation) {
                    $normalizedData['description_'.$translation['locale']] = $translation['description'];
                }
            }

            unset($normalizedData['translations']);
        }

        if ($isAdmin) {
            if (!array_key_exists('type', $normalizedData)) {
                throw new LogicException('The Release data must have a type.');
            }

            $normalizedData['type'] = ReleaseType::tryFrom($normalizedData['type']);
            if (!$normalizedData['type'] instanceof ReleaseType) {
                throw new LogicException('The Release data type is unknown.');
            }

            $normalizedData['type'] = $normalizedData['type']->name;
        }

        return $normalizedData;
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
