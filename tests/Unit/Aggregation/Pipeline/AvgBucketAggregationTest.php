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

use OpenSearchDSL\Aggregation\Pipeline\AvgBucketAggregation;

/**
 * Unit test for avg_bucket aggregation.
 *
 * @internal
 */
class AvgBucketAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests getArray method.
     */
    public function testGetArray(): void
    {
        $aggregation = new AvgBucketAggregation('foo', 'foo>bar');

        static::assertEquals(['buckets_path' => 'foo>bar'], $aggregation->getArray());
    }

    /**
     * Tests getType method.
     */
    public function testAvgBucketAggregationGetType(): void
    {
        $aggregation = new AvgBucketAggregation('foo', 'foo>bar');
        static::assertEquals('avg_bucket', $aggregation->getType());
    }
}
