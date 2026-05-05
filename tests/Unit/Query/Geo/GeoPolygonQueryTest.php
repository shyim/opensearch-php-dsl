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

use OpenSearchDSL\Query\Geo\GeoPolygonQuery;

/**
 * @internal
 */
class GeoPolygonQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests toArray method.
     *
     * @param string $field field name
     * @param array $points polygon's points
     * @param array $parameters optional parameters
     * @param array $expected expected result
     *
     * @dataProvider getArrayDataProvider
     */
    public function testToArray($field, $points, $parameters, $expected): void
    {
        $filter = new GeoPolygonQuery($field, $points, $parameters);
        $result = $filter->toArray();
        static::assertEquals(['geo_polygon' => $expected], $result);
    }

    /**
     * Data provider to testToArray.
     *
     * @return array
     */
    public function getArrayDataProvider()
    {
        return [
            // Case #1.
            [
                'location',
                [
                    ['lat' => 20, 'lon' => -80],
                    ['lat' => 30, 'lon' => -40],
                    ['lat' => 70, 'lon' => -90],
                ],
                [],
                [
                    'location' => [
                        'points' => [
                            ['lat' => 20, 'lon' => -80],
                            ['lat' => 30, 'lon' => -40],
                            ['lat' => 70, 'lon' => -90],
                        ],
                    ],
                ],
            ],
            // Case #2.
            [
                'location',
                [],
                ['parameter' => 'value'],
                [
                    'location' => ['points' => []],
                    'parameter' => 'value',
                ],
            ],
            // Case #3.
            [
                'location',
                [
                    ['lat' => 20, 'lon' => -80],
                ],
                ['parameter' => 'value'],
                [
                    'location' => [
                        'points' => [['lat' => 20, 'lon' => -80]],
                    ],
                    'parameter' => 'value',
                ],
            ],
        ];
    }
}
