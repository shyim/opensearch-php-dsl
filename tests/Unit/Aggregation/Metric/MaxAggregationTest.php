<?php declare(strict_types=1);

namespace OpenSearchDSL\Tests\Unit\Aggregation\Metric;

use OpenSearchDSL\Aggregation\Metric\MaxAggregation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class MaxAggregationTest extends TestCase
{
    public function testConstructor(): void
    {
        $a = new MaxAggregation('test', 'test');
        static::assertSame([
            'max' => [
                'field' => 'test',
            ],
        ], $a->toArray());
    }
}
