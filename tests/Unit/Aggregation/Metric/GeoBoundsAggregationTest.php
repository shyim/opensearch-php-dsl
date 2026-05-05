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

use OpenSearchDSL\Aggregation\Metric\GeoBoundsAggregation;

/**
 * Unit test for geo bounds aggregation.
 *
 * @internal
 */
class GeoBoundsAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests getType method.
     */
    public function testGeoBoundsAggregationGetType(): void
    {
        $agg = new GeoBoundsAggregation('foo', 'field');
        $result = $agg->getType();
        static::assertEquals('geo_bounds', $result);
        static::assertSame('foo', $agg->getName());
        static::assertSame('field', $agg->getField());
        static::assertTrue($agg->isWrapLongitude());
        $agg->setWrapLongitude(false);
        static::assertFalse($agg->isWrapLongitude());

        static::assertSame([
            'field' => 'field',
            'wrap_longitude' => false,
        ], $agg->getArray());

        $bla = new GeoBoundsAggregation('foo', 'field', false);
        static::assertFalse($bla->isWrapLongitude());
    }

    /**
     * Tests getArray method.
     */
    public function testGeoBoundsAggregationGetArray(): void
    {
        $agg = new GeoBoundsAggregation('foo', '');
        $agg->setField('bar');
        $agg->setWrapLongitude(true);
        $result = [
            'geo_bounds' => [
                'field' => 'bar',
                'wrap_longitude' => true,
            ],
        ];
        static::assertEquals($result, $agg->toArray(), 'when wraplongitude is true');

        $agg->setWrapLongitude(false);
        $result = [
            'geo_bounds' => [
                'field' => 'bar',
                'wrap_longitude' => false,
            ],
        ];
        static::assertEquals($result, $agg->toArray(), 'when wraplongitude is false');
    }
}
