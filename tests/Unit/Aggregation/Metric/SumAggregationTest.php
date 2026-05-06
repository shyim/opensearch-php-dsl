<?php declare(strict_types=1);

namespace OpenSearchDSL\Tests\Unit\Aggregation\Metric;

use OpenSearchDSL\Aggregation\Metric\SumAggregation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class SumAggregationTest extends TestCase
{
    public function testConstructor(): void
    {
        $a = new SumAggregation('test', 'test');
        static::assertSame([
            'sum' => [
                'field' => 'test',
            ],
        ], $a->toArray());
    }
}
