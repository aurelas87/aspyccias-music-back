<?php

namespace App\Helper;

use App\Model\PaginatedList;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaginationHelper
{
    public const int DEFAULT_OFFSET = 0;
    public const int DEFAULT_LIMIT = 6;
    public const int DEFAULT_ADMIN_LIMIT = 10;
    public const string DEFAULT_SORT_FIELD = 'date';
    public const string DEFAULT_SORT_ORDER = 'desc';

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

    public function parseQueryParameters(array $queryParameters, bool $isAdmin): void
    {
        $resolver = new OptionsResolver();

        $resolver
            ->setDefault('offset', self::DEFAULT_OFFSET)
            ->addAllowedTypes('offset', ['int', 'string'])
            ->addNormalizer('offset', function (Options $options, string $value) {
                $intValue = (int)$value;

                if ($value !== (string)$intValue) {
                    $intValue = PaginationHelper::DEFAULT_OFFSET;
                }

                return max($intValue, 0);
            });

        $options = $resolver->resolve($queryParameters);

        $this->offset = $options['offset'];

        if ($isAdmin) {
            $this->limit = self::DEFAULT_ADMIN_LIMIT;
        }
    }

    public function calculateMaxOffset(int $total): void
    {
        $this->maxOffset = $total > $this->limit
            ? $this->limit * ((int)floor($total / $this->limit))
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

        return $nextOffset >= $total ? null : $nextOffset;
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
