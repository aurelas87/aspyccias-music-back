<?php

namespace App\ValueResolver\News;

use App\Entity\News\News;
use App\Exception\News\NewsNotFoundException;
use App\Repository\News\NewsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

#[AsTargetedValueResolver('news')]
readonly class NewsValueResolver implements ValueResolverInterface
{
    public function __construct(private NewsRepository $newsRepository)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();
        if (!$argumentType || $argumentType !== News::class) {
            return [];
        }

        $value = $request->attributes->get($argument->getName());
        if (!\is_string($value)) {
            return [];
        }

        $news = $this->newsRepository->findOneBy(['slug' => $value]);
        if (!$news) {
            throw new NewsNotFoundException();
        }

        return [$news];
    }
}
