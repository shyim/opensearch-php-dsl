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

use OpenSearchDSL\Aggregation\Pipeline\MaxBucketAggregation;

/**
 * Unit test for max bucket aggregation.
 *
 * @internal
 */
class MaxBucketAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests toArray method.
     */
    public function testToArray(): void
    {
        $aggregation = new MaxBucketAggregation('acme', 'test');

        $expected = [
            'max_bucket' => [
                'buckets_path' => 'test',
            ],
        ];

        static::assertEquals($expected, $aggregation->toArray());
    }
}
