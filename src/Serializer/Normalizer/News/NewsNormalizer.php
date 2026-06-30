<?php

namespace App\Serializer\Normalizer\News;

use App\Entity\News\News;
use LogicException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class NewsNormalizer implements NormalizerInterface
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

        if (!array_key_exists('translations', $normalizedData) || (!$isAdmin && count($normalizedData['translations']) !== 1)) {
            throw new LogicException('The News data must have at least one translation.');
        }

        if (!$isAdmin) {
            $normalizedData['title'] = $normalizedData['translations'][0]['title'];

            if (in_array('details', $context['groups'], true)) {
                $normalizedData['content'] = $normalizedData['translations'][0]['content'];
            }

        } else {
            foreach ($normalizedData['translations'] as $translation) {
                $normalizedData['title_'.$translation['locale']] = $translation['title'];

                if (in_array('details', $context['groups'], true)) {
                    $normalizedData['content_'.$translation['locale']] = $translation['content'];
                }
            }
        }

        unset($normalizedData['translations']);

        return $normalizedData;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof News;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [News::class => true];
    }
}
