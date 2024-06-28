<?php

namespace App\Tests\Helper;

use App\Helper\PaginationHelper;
use PHPUnit\Framework\TestCase;
use Random\RandomException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

class PaginationHelperTest extends TestCase
{
    private PaginationHelper $paginationHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->paginationHelper = new PaginationHelper();
    }

    /**
     * @throws RandomException
     */
    public function dataProviderParseQueryParameters(): array
    {
        $validOffset = \random_int(0, PHP_INT_MAX);

        return [
            'default if empty' => [
                [],
                'expected' => PaginationHelper::DEFAULT_OFFSET,
            ],
            'valid offset' => [
                ['offset' => $validOffset],
                'expected' => $validOffset,
            ],
            'valid offset as string' => [
                ['offset' => '3'],
                'expected' => 3,
            ],
            'offset below 0' => [
                ['offset' => \random_int(-30, -1)],
                'expected' => PaginationHelper::DEFAULT_OFFSET,
            ],
            'offset above max int as string' => [
                ['offset' => (string)(PHP_INT_MAX + 2)],
                'expected' => PaginationHelper::DEFAULT_OFFSET,
            ],
            'offset float as string' => [
                ['offset' => '1.2'],
                'expected' => PaginationHelper::DEFAULT_OFFSET,
            ],
            'offset string' => [
                ['offset' => 'abc'],
                'expected' => PaginationHelper::DEFAULT_OFFSET,
            ],
        ];
    }

    /**
     * @dataProvider dataProviderParseQueryParameters
     */
    public function testParseQueryParameters(array $queryParameters, int $expected): void
    {
        $this->paginationHelper->parseQueryParameters($queryParameters);

        static::assertSame($expected, $this->paginationHelper->getOffset());
        static::assertSame(PaginationHelper::DEFAULT_LIMIT, $this->paginationHelper->getLimit());
    }

    public function dataProviderInvalidType(): array
    {
        return [
            'offset as float' => [
                ['offset' => 1.2],
                'expected_exception_message' => '"offset"',
            ],
            'offset above max int' => [
                ['offset' => PHP_INT_MAX + 2], // will be transformed to float
                'expected_exception_message' => '"offset"',
            ],
            'offset as array' => [
                ['offset' => []],
                'expected_exception_message' => '"offset"',
            ],
        ];
    }

    /**
     * @dataProvider dataProviderInvalidType
     */
    public function testParseQueryParametersInvalidTypeThrowsException(
        array $queryParameters,
        $expectedExceptionMessage
    ): void {
        static::expectException(InvalidOptionsException::class);
        static::expectExceptionMessage($expectedExceptionMessage);

        $this->paginationHelper->parseQueryParameters($queryParameters);
    }

    public function dataProviderMapItemsToPaginatedList(): array
    {
        $useCases = [];

        $total = 13;
        $nbPages = \ceil($total / PaginationHelper::DEFAULT_LIMIT);

        for ($indexPage = 1; $indexPage <= $nbPages; $indexPage++) {
            $offset = ($indexPage - 1) * PaginationHelper::DEFAULT_LIMIT;

            $useCases["Page $indexPage"] = [
                'total' => $total,
                'offset' => $offset,
                'nbItems' => \min($total - $offset, PaginationHelper::DEFAULT_LIMIT),
                'expected_previous_offset' => $indexPage > 1 ? $offset - PaginationHelper::DEFAULT_LIMIT : null,
                'expected_next_offset' => $indexPage < $nbPages ? $offset + PaginationHelper::DEFAULT_LIMIT : null,
            ];
        }

        $useCases['No item'] = [
            'total' => 0,
            'offset' => 0,
            'nbItems' => 0,
            'expected_previous_offset' => null,
            'expected_next_offset' => null,
        ];

        $useCases['Offset above total'] = [
            'total' => $total,
            'offset' => $total + 1,
            'nbItems' => 0,
            'expected_previous_offset' => 10,
            'expected_next_offset' => null,
        ];

        return $useCases;
    }

    /**
     * @dataProvider dataProviderMapItemsToPaginatedList
     */
    public function testMapItemsToPaginatedList(
        int $total,
        int $offset,
        int $nbItems,
        ?int $expectedPreviousOffset,
        ?int $expectedNextOffset
    ): void {
        $this->paginationHelper->parseQueryParameters(['offset' => $offset]);

        $items = [];
        for ($indexItem = 1; $indexItem <= $nbItems; $indexItem++) {
            $items[] = new MockPaginatedListItem();
        }
        $paginatedList = $this->paginationHelper->mapItemsToPaginatedList([
            'items' => $items,
            'total' => $total,
        ]);

        static::assertCount($nbItems, $paginatedList->getItems());
        static::assertSame($expectedPreviousOffset, $paginatedList->getPreviousOffset());
        static::assertSame($expectedNextOffset, $paginatedList->getNextOffset());
    }
}
