<?php declare(strict_types=1);

namespace OpenSearchDSL\Tests\Unit\Aggregation\Metric;

use OpenSearchDSL\Aggregation\Metric\ScriptedMetricAggregation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class ScriptedMetricAggregationTest extends TestCase
{
    public function testConstructor(): void
    {
        $a = new ScriptedMetricAggregation('test', 'init', 'map', 'combine', 'reduce');
        static::assertSame([
            'scripted_metric' => [
                'init_script' => 'init',
                'map_script' => 'map',
                'combine_script' => 'combine',
                'reduce_script' => 'reduce',
            ],
        ], $a->toArray());
        static::assertSame('scripted_metric', $a->getType());
        static::assertSame('test', $a->getName());
        static::assertSame('init', $a->getInitScript());
        static::assertSame('map', $a->getMapScript());
        static::assertSame('combine', $a->getCombineScript());
        static::assertSame('reduce', $a->getReduceScript());

        $a->setCombineScript('combine2');
        static::assertSame('combine2', $a->getCombineScript());

        $a->setInitScript('init2');
        static::assertSame('init2', $a->getInitScript());

        $a->setMapScript('map2');
        static::assertSame('map2', $a->getMapScript());

        $a->setReduceScript('reduce2');
        static::assertSame('reduce2', $a->getReduceScript());

        $a->setReduceScript('');

        static::assertSame([
            'scripted_metric' => [
                'init_script' => 'init2',
                'map_script' => 'map2',
                'combine_script' => 'combine2',
            ],
        ], $a->toArray());

        static::assertSame([
            'init_script' => 'init2',
            'map_script' => 'map2',
            'combine_script' => 'combine2',
        ], $a->getArray());
    }
}
