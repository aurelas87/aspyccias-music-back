<?php

namespace App\Serializer\Normalizer\Release;

use App\Entity\Release\Release;
use App\Model\Release\ReleaseType;
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
        $isAdmin = \in_array('admin', $context['groups'], true);

        if (\in_array('details', $context['groups'], true)) {
            if (!\array_key_exists('translations', $data) || (!$isAdmin && \count($data['translations']) !== 1)) {
                throw new \LogicException('The Release data must have at least one translation.');
            }

            if (!$isAdmin) {
                $data['description'] = $data['translations'][0]['description'];
            } else {
                foreach ($data['translations'] as $translation) {
                    $data['description_'.$translation['locale']] = $translation['description'];
                }
            }

            unset($data['translations']);
        }

        if ($isAdmin) {
            if (!\array_key_exists('type', $data)) {
                throw new \LogicException('The Release data must have a type.');
            }

            $data['type'] = ReleaseType::tryFrom($data['type']);
            if (!$data['type'] instanceof ReleaseType) {
                throw new \LogicException('The Release data type is unknown.');
            }

            $data['type'] = $data['type']->name;
        }

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
