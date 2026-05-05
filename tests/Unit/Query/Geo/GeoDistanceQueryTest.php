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

use OpenSearchDSL\Query\Geo\GeoDistanceQuery;
use OpenSearchDSL\Type\Location;

/**
 * @internal
 */
class GeoDistanceQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests toArray() method.
     *
     * @param string $field field name
     * @param string $distance distance
     * @param array $location location
     * @param array $parameters optional parameters
     * @param array $expected expected result
     *
     * @dataProvider getArrayDataProvider
     */
    public function testToArray($field, $distance, $location, $parameters, $expected): void
    {
        $query = new GeoDistanceQuery($field, $distance, new Location($location['lat'], $location['lon']), $parameters);
        $result = $query->toArray();
        static::assertEquals(['geo_distance' => $expected], $result);
    }

    /**
     * Data provider for testToArray().
     *
     * @return array
     */
    public function getArrayDataProvider()
    {
        return [
            // Case #1.
            [
                'location',
                '200km',
                ['lat' => 40, 'lon' => -70],
                [],
                ['distance' => '200km', 'location' => ['lat' => 40, 'lon' => -70]],
            ],
            // Case #2.
            [
                'location',
                '20km',
                ['lat' => 0, 'lon' => 0],
                ['parameter' => 'value'],
                ['distance' => '20km', 'location' => ['lat' => 0, 'lon' => 0], 'parameter' => 'value'],
            ],
        ];
    }
}
