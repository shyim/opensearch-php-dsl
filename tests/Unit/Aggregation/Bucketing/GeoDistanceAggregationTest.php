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

use OpenSearchDSL\Aggregation\Bucketing\GeoDistanceAggregation;

/**
 * @internal
 */
class GeoDistanceAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests getArray method.
     *
     * @param array $filterData
     * @param array $expected
     *
     * @dataProvider getGeoDistanceAggregationGetArrayDataProvider
     */
    public function testGeoDistanceAggregationGetArray($filterData, $expected): void
    {
        $aggregation = new GeoDistanceAggregation('foo', '', '');
        $aggregation->setOrigin($filterData['origin']);
        $aggregation->setField($filterData['field']);
        $aggregation->setUnit($filterData['unit']);
        $aggregation->setDistanceType($filterData['distance_type']);
        $aggregation->addRange($filterData['ranges'][0], $filterData['ranges'][1]);

        $result = $aggregation->getArray();
        static::assertEquals($result, $expected);
        static::assertSame('foo', $aggregation->getName());
        static::assertSame($filterData['field'], $aggregation->getField());
        static::assertSame($filterData['origin'], $aggregation->getOrigin());
        static::assertSame($filterData['unit'], $aggregation->getUnit());
        static::assertSame($filterData['distance_type'], $aggregation->getDistanceType());
    }

    /**
     * Data provider for testGeoDistanceAggregationGetArray().
     *
     * @return array
     */
    public function getGeoDistanceAggregationGetArrayDataProvider()
    {
        $out = [];
        $filterData = [
            'field' => 'location',
            'origin' => '52.3760, 4.894',
            'unit' => 'mi',
            'distance_type' => 'plane',
            'ranges' => [100, 300],
        ];

        $expectedResults = [
            'field' => 'location',
            'origin' => '52.3760, 4.894',
            'unit' => 'mi',
            'distance_type' => 'plane',
            'ranges' => [['from' => 100, 'to' => 300]],
        ];

        $out[] = [$filterData, $expectedResults];

        return $out;
    }

    /**
     * Tests getType method.
     */
    public function testGeoDistanceAggregationGetType(): void
    {
        $aggregation = new GeoDistanceAggregation('foo', '', '');
        $result = $aggregation->getType();
        static::assertEquals('geo_distance', $result);
    }

    /**
     * Tests if parameters can be passed to constructor.
     */
    public function testConstructorFilter(): void
    {
        $aggregation = new GeoDistanceAggregation(
            'test',
            'fieldName',
            'originValue',
            [
                ['from' => 20],
                ['to' => 20],
                ['from' => 20, 'to' => 30],
            ],
            'unitValue',
            'distanceTypeValue'
        );

        static::assertSame(
            [
                'geo_distance' => [
                    'field' => 'fieldName',
                    'origin' => 'originValue',
                    'unit' => 'unitValue',
                    'distance_type' => 'distanceTypeValue',
                    'ranges' => [
                        ['from' => 20.0],
                        ['to' => 20.0],
                        ['from' => 20.0, 'to' => 30.0],
                    ],
                ],
            ],
            $aggregation->toArray()
        );
    }

    public function testWithoutDistance(): void
    {
        static::expectException(\LogicException::class);
        new GeoDistanceAggregation(
            'test',
            'fieldName',
            'originValue',
            [
                [
                    'from' => null,
                    'to' => null,
                ],
            ]
        );
    }
}
