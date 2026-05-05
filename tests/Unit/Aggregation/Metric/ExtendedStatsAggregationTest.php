<?php declare(strict_types=1);

namespace OpenSearchDSL\Tests\Unit\Aggregation\Metric;

use OpenSearchDSL\Aggregation\Metric\ExtendedStatsAggregation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class ExtendedStatsAggregationTest extends TestCase
{
    public function testConstructor(): void
    {
        $a = new ExtendedStatsAggregation('test', 'test', 5, 'script');
        static::assertSame('test', $a->getName());
        static::assertSame('test', $a->getField());
        static::assertSame(5, $a->getSigma());
        static::assertSame('script', $a->getScript());
        static::assertSame([
            'extended_stats' => [
                'field' => 'test',
                'script' => 'script',
                'sigma' => 5,
            ],
        ], $a->toArray());

        $a->setSigma(10);
        static::assertSame(10, $a->getSigma());

        $a->setSigma(null);

        static::assertSame([
            'field' => 'test',
            'script' => 'script',
        ], $a->getArray());
    }

    public function testIdScript(): void
    {
        $idScript = ['id' => 'scriptId', 'params' => ['param' => 'value']];
        $aggregation = new ExtendedStatsAggregation('foo', 'fieldValue', 5, $idScript);
        static::assertSame(
            [
                'extended_stats' => [
                    'field' => 'fieldValue',
                    'script' => $idScript,
                    'sigma' => 5,
                ],
            ],
            $aggregation->toArray()
        );
        static::assertSame('foo', $aggregation->getName());
        static::assertSame('fieldValue', $aggregation->getField());
        static::assertSame(5, $aggregation->getSigma());
        static::assertSame($idScript, $aggregation->getScript());

        static::assertSame([
            'field' => 'fieldValue',
            'script' => $idScript,
            'sigma' => 5,
        ], $aggregation->getArray());
    }
}
