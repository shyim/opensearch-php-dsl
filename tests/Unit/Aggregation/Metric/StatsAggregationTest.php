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

use OpenSearchDSL\Aggregation\Metric\StatsAggregation;

/**
 * @internal
 */
class StatsAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test for stats aggregation toArray() method.
     */
    public function testToArray(): void
    {
        $aggregation = new StatsAggregation('test_agg');
        $aggregation->setField('test_field');

        $expectedResult = [
            'stats' => ['field' => 'test_field'],
        ];

        static::assertEquals($expectedResult, $aggregation->toArray());
    }

    /**
     * Tests if parameter can be passed to constructor.
     */
    public function testConstructor(): void
    {
        $aggregation = new StatsAggregation('foo', 'fieldValue', 'scriptValue');
        static::assertSame(
            [
                'stats' => [
                    'field' => 'fieldValue',
                    'script' => 'scriptValue',
                ],
            ],
            $aggregation->toArray()
        );
        static::assertSame('foo', $aggregation->getName());
        static::assertSame('fieldValue', $aggregation->getField());
        static::assertSame('scriptValue', $aggregation->getScript());

        static::assertSame([
            'field' => 'fieldValue',
            'script' => 'scriptValue',
        ], $aggregation->getArray());

        $aggregation->setScript('');

        static::assertSame([
            'field' => 'fieldValue',
        ], $aggregation->getArray());
    }

    public function testIdScript(): void
    {
        $idScript = ['id' => 'scriptId', 'params' => ['param' => 'value']];
        $aggregation = new StatsAggregation('foo', 'fieldValue', $idScript);
        static::assertSame(
            [
                'stats' => [
                    'field' => 'fieldValue',
                    'script' => $idScript,
                ],
            ],
            $aggregation->toArray()
        );
        static::assertSame('foo', $aggregation->getName());
        static::assertSame('fieldValue', $aggregation->getField());
        static::assertSame($idScript, $aggregation->getScript());

        static::assertSame([
            'field' => 'fieldValue',
            'script' => $idScript,
        ], $aggregation->getArray());
    }
}
