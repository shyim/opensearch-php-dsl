<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\Aggregation\Bucketing;

use OpenSearchDSL\Aggregation\Bucketing\GeoHashGridAggregation;

/**
 * Unit test for geohash grid aggregation.
 *
 * @internal
 */
class GeoHashGridAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests getArray method.
     *
     * @param array $filterData
     * @param array $expected
     *
     * @dataProvider getArrayDataProvider
     */
    public function testGeoHashGridAggregationGetArray($filterData, $expected): void
    {
        $aggregation = new GeoHashGridAggregation('foo', 'field');
        static::assertSame('foo', $aggregation->getName());
        static::assertSame('field', $aggregation->getField());
        $aggregation->setPrecision($filterData['precision']);
        $aggregation->setSize($filterData['size']);
        $aggregation->setShardSize($filterData['shard_size']);
        $aggregation->setField($filterData['field']);

        $result = $aggregation->getArray();
        static::assertEquals($result, $expected);
        static::assertSame('location', $aggregation->getField());
    }

    /**
     * Data provider for testGeoHashGridAggregationGetArray().
     *
     * @return array
     */
    public function getArrayDataProvider()
    {
        $out = [];

        $filterData = [
            'field' => 'location',
            'precision' => 3,
            'size' => 10,
            'shard_size' => 10,
        ];

        $expectedResults = [
            'field' => 'location',
            'precision' => 3,
            'size' => 10,
            'shard_size' => 10,
        ];

        $out[] = [$filterData, $expectedResults];

        return $out;
    }

    /**
     * Tests getType method.
     */
    public function testGeoHashGridAggregationGetType(): void
    {
        $aggregation = new GeoHashGridAggregation('foo', 'field', 1, 2, 3);
        $result = $aggregation->getType();
        static::assertEquals('geohash_grid', $result);
        static::assertSame('field', $aggregation->getField());
        static::assertSame(1, $aggregation->getPrecision());
        static::assertSame(2, $aggregation->getSize());
        static::assertSame(3, $aggregation->getShardSize());
    }
}
