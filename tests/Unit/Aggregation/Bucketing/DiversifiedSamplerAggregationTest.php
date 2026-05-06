<?php declare(strict_types=1);

namespace OpenSearchDSL\Tests\Unit\Aggregation\Bucketing;

use OpenSearchDSL\Aggregation\Bucketing\DiversifiedSamplerAggregation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class DiversifiedSamplerAggregationTest extends TestCase
{
    public function testConstructor(): void
    {
        $a = new DiversifiedSamplerAggregation('test', 'test', 5);
        static::assertSame('test', $a->getName());
        static::assertSame('test', $a->getField());
        static::assertSame(5, $a->getShardSize());

        static::assertSame([
            'diversified_sampler' => [
                'field' => 'test',
                'shard_size' => 5,
            ],
        ], $a->toArray());
        static::assertSame('test', $a->getField());
        static::assertSame(5, $a->getShardSize());

        $a->setField('');
        static::assertSame([
            'diversified_sampler' => [
                'shard_size' => 5,
            ],
        ], $a->toArray());

        $a->setShardSize(0);
        static::assertSame(0, $a->getShardSize());
    }
}
