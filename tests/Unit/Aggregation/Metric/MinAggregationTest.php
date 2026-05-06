<?php declare(strict_types=1);

namespace OpenSearchDSL\Tests\Unit\Aggregation\Metric;

use OpenSearchDSL\Aggregation\Metric\MinAggregation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class MinAggregationTest extends TestCase
{
    public function testConstructor(): void
    {
        $a = new MinAggregation('test', 'test');
        static::assertSame([
            'min' => [
                'field' => 'test',
            ],
        ], $a->toArray());
    }
}
