<?php

namespace App\Serializer\Normalizer\News;

use App\Entity\News\News;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class NewsNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer
    ) {
    }

    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        if (!\array_key_exists('translations', $data) || \count($data['translations']) !== 1) {
            throw new \LogicException('The News data must have at least one translation.');
        }

        $data['title'] = $data['translations'][0]['title'];

        if (\in_array('details', $context['groups'], true)) {
            $data['content'] = $data['translations'][0]['content'];
        }

        unset($data['translations']);

        return $data;
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
