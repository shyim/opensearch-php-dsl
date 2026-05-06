<?php declare(strict_types=1);

namespace OpenSearchDSL\Tests\Unit\Aggregation\Metric;

use OpenSearchDSL\Aggregation\Metric\AvgAggregation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class AvgAggregationTest extends TestCase
{
    public function testConstructor(): void
    {
        $a = new AvgAggregation('test', 'test');

        static::assertSame([
            'avg' => [
                'field' => 'test',
            ],
        ], $a->toArray());
    }
}
