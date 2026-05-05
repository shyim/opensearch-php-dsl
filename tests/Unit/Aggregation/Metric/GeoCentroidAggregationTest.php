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

use OpenSearchDSL\Aggregation\Metric\GeoCentroidAggregation;

/**
 * Unit test for children aggregation.
 *
 * @internal
 */
class GeoCentroidAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests getType method.
     */
    public function testGeoCentroidAggregationGetType(): void
    {
        $aggregation = new GeoCentroidAggregation('foo', 'field');
        static::assertEquals('geo_centroid', $aggregation->getType());
        static::assertSame('foo', $aggregation->getName());
        static::assertSame('field', $aggregation->getField());
    }

    /**
     * Tests getArray method.
     */
    public function testGeoCentroidAggregationGetArray(): void
    {
        $aggregation = new GeoCentroidAggregation('foo', 'location');
        static::assertEquals(['field' => 'location'], $aggregation->getArray());
    }
}
