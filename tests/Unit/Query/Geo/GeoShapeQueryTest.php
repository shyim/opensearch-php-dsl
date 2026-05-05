<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\Query\Geo;

use OpenSearchDSL\Query\Geo\GeoShapeQuery;

/**
 * @internal
 */
class GeoShapeQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests toArray() method.
     */
    public function testToArray(): void
    {
        $filter = new GeoShapeQuery(['param1' => 'value1']);
        $filter->addShape(
            'location',
            GeoShapeQuery::SHAPE_TYPE_ENVELOPE,
            [[13, 53], [14, 52]],
            GeoShapeQuery::INTERSECTS,
            ['test' => 1]
        );

        $expected = [
            'geo_shape' => [
                'location' => [
                    'shape' => [
                        'type' => 'envelope',
                        'coordinates' => [[13, 53], [14, 52]],
                        'test' => 1,
                    ],
                    'relation' => 'intersects',
                ],
                'param1' => 'value1',
            ],
        ];

        static::assertEquals($expected, $filter->toArray());
    }

    /**
     * Test for toArray() in case of pre-indexed shape.
     */
    public function testToArrayIndexed(): void
    {
        $filter = new GeoShapeQuery(['param1' => 'value1']);
        $filter->addPreIndexedShape('location', 'DEU', 'countries', 'shapes', 'location', GeoShapeQuery::WITHIN, ['test' => 1]);

        $expected = [
            'geo_shape' => [
                'location' => [
                    'indexed_shape' => [
                        'id' => 'DEU',
                        'type' => 'countries',
                        'index' => 'shapes',
                        'path' => 'location',
                        'test' => 1,
                    ],
                    'relation' => 'within',
                ],
                'param1' => 'value1',
            ],
        ];

        static::assertEquals($expected, $filter->toArray());
    }
}
