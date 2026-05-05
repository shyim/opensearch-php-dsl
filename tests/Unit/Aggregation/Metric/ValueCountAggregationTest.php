<?php declare(strict_types=1);

namespace OpenSearchDSL\Tests\Unit\Aggregation\Metric;

use OpenSearchDSL\Aggregation\Metric\ValueCountAggregation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class ValueCountAggregationTest extends TestCase
{
    public function testConstructor(): void
    {
        $a = new ValueCountAggregation('test', 'test');
        static::assertSame([
            'value_count' => [
                'field' => 'test',
            ],
        ], $a->toArray());
    }
}
