<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\Aggregation\Pipeline;

use OpenSearchDSL\Aggregation\Pipeline\BucketSortAggregation;
use OpenSearchDSL\Sort\FieldSort;

/**
 * Unit test for the bucket sort aggregation.
 *
 * @internal
 */
class BucketSortAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests toArray method.
     */
    public function testToArray(): void
    {
        $aggregation = new BucketSortAggregation('acme', 'test');

        $expected = [
            'bucket_sort' => [
                'buckets_path' => 'test',
            ],
        ];

        static::assertEquals($expected, $aggregation->toArray());

        $aggregation = new BucketSortAggregation('acme');

        $expected = [
            'bucket_sort' => [],
        ];

        static::assertEquals($expected, $aggregation->toArray());

        $aggregation = new BucketSortAggregation('acme');
        $sort = new FieldSort('test_field', FieldSort::ASC);
        $aggregation->addSort($sort);

        $expected = [
            'bucket_sort' => [
                'sort' => [
                    [
                        'test_field' => ['order' => 'asc'],
                    ],
                ],
            ],
        ];

        static::assertEquals($expected, $aggregation->toArray());
    }

    public function testSetSort(): void
    {
        $aggregation = new BucketSortAggregation('acme', 'test');
        $aggregation->setSort(['test']);
        static::assertSame(['test'], $aggregation->getSort());
    }
}
