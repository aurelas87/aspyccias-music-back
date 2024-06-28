<?php

namespace App\Helper;

use App\Model\PaginatedList;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaginationHelper
{
    public const DEFAULT_OFFSET = 0;
    public const DEFAULT_LIMIT = 5;
    public const DEFAULT_SORT_FIELD = 'date';
    public const DEFAULT_SORT_ORDER = 'desc';

    private int $offset = self::DEFAULT_OFFSET;
    private int $maxOffset = self::DEFAULT_OFFSET;
    private int $limit = self::DEFAULT_LIMIT;

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function parseQueryParameters(array $queryParameters): void
    {
        $resolver = new OptionsResolver();

        $resolver
            ->setDefault('offset', self::DEFAULT_OFFSET)
            ->addAllowedTypes('offset', ['int', 'string'])
            ->addNormalizer('offset', function (Options $options, string $value) {
                $intValue = (int) $value;

                if ($value != $intValue) {
                    $value = PaginationHelper::DEFAULT_OFFSET;
                }

                return \max($value, 0);
            });

        $options = $resolver->resolve($queryParameters);

        $this->offset = $options['offset'];
    }

    public function calculateMaxOffset(int $total): void
    {
        $this->maxOffset = $total > self::DEFAULT_LIMIT
            ? self::DEFAULT_LIMIT * ((int)\round($total / self::DEFAULT_LIMIT) - 1)
            : self::DEFAULT_OFFSET;
    }

    public function calculatePreviousOffset(): ?int
    {
        if ($this->offset > $this->maxOffset) {
            return $this->maxOffset;
        }

        $previousOffset = $this->offset - $this->limit;

        return $previousOffset < 0 ? null : $previousOffset;
    }

    public function calculateNextOffset(int $total): ?int
    {
        $nextOffset = $this->offset + $this->limit;

        return $nextOffset > $total ? null : $nextOffset;
    }

    public function mapItemsToPaginatedList($newsItems): PaginatedList
    {
        $this->calculateMaxOffset($newsItems['total']);

        $listNews = new PaginatedList();
        $listNews->setPreviousOffset($this->calculatePreviousOffset());
        $listNews->setNextOffset($this->calculateNextOffset($newsItems['total']));
        $listNews->setItems($newsItems['items']);

        return $listNews;
    }
}
