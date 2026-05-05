<?php declare(strict_types=1);

namespace OpenSearchDSL\Tests\Unit\Aggregation\Pipeline;

use OpenSearchDSL\Aggregation\Pipeline\MovingAverageAggregation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class MovingAverageAggregationTest extends TestCase
{
    public function testConstructor(): void
    {
        $a = new MovingAverageAggregation('test', 'test');

        static::assertSame([
            'moving_avg' => [
                'buckets_path' => 'test',
            ],
        ], $a->toArray());
    }
}
