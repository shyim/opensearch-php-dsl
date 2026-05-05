<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\Aggregation\Metric;

use OpenSearchDSL\Aggregation\Bucketing\TermsAggregation;
use OpenSearchDSL\Aggregation\Metric\TopHitsAggregation;
use OpenSearchDSL\Sort\FieldSort;

/**
 * Unit tests for top hits aggregation.
 *
 * @internal
 */
class TopHitsAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Check if aggregation returns the expected array.
     */
    public function testToArray(): void
    {
        $sort = new FieldSort('acme', FieldSort::ASC);
        $aggregation = new TopHitsAggregation('acme', 1, 1, $sort);

        $expected = [
            'top_hits' => [
                'sort' => [
                    ['acme' => ['order' => 'asc']],
                ],
                'size' => 1,
                'from' => 1,
            ],
        ];

        static::assertSame($expected, $aggregation->toArray());
    }

    /**
     * Check if parameters can be set to agg.
     */
    public function testParametersAddition(): void
    {
        $aggregation = new TopHitsAggregation('acme', 0, 1);
        $aggregation->addParameter('_source', ['include' => ['title']]);

        $expected = [
            'top_hits' => [
                'size' => 0,
                'from' => 1,
                '_source' => [
                    'include' => ['title'],
                ],
            ],
        ];

        static::assertSame($expected, $aggregation->toArray());
    }

    public function testSort(): void
    {
        $aggregation = new TopHitsAggregation('acme', 0, 1);

        $sorts = [new TermsAggregation('test', 'test')];

        $aggregation->setSorts($sorts);
        static::assertSame($sorts, $aggregation->getSorts());
        static::assertSame('acme', $aggregation->getName());
        static::assertSame(0, $aggregation->getSize());
        static::assertSame(1, $aggregation->getFrom());

        $aggregation->setFrom(50);
        static::assertSame(50, $aggregation->getFrom());

        $aggregation->setSize(100);
        static::assertSame(100, $aggregation->getSize());

        static::assertSame([
            'sort' => [
                ['terms' => ['field' => 'test']],
            ],
            'size' => 100,
            'from' => 50,
        ], $aggregation->getArray());

        $aggregation->setSorts($sorts);

        static::assertSame([
            'sort' => [
                ['terms' => ['field' => 'test']],
            ],
            'size' => 100,
            'from' => 50,
        ], $aggregation->getArray());

        $aggregation->addSort(new FieldSort('test', FieldSort::DESC));
        static::assertSame([
            'sort' => [
                ['terms' => ['field' => 'test']],
                ['test' => ['order' => 'desc']],
            ],
            'size' => 100,
            'from' => 50,
        ], $aggregation->getArray());
    }
}
