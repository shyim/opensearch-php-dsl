<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\Aggregation\Bucketing;

use OpenSearchDSL\Aggregation\Bucketing\SamplerAggregation;
use OpenSearchDSL\Aggregation\Bucketing\TermsAggregation;

/**
 * Unit test for children aggregation.
 *
 * @internal
 */
class SamplerAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests getType method.
     */
    public function testGetType(): void
    {
        $aggregation = new SamplerAggregation('foo');
        $result = $aggregation->getType();
        static::assertEquals('sampler', $result);
    }

    /**
     * Tests toArray method.
     */
    public function testToArray(): void
    {
        $termAggregation = new TermsAggregation('acme');

        $aggregation = new SamplerAggregation('foo', 'field', 25);
        static::assertSame('foo', $aggregation->getName());
        static::assertSame('field', $aggregation->getField());
        static::assertSame(25, $aggregation->getShardSize());
        static::assertSame(['field' => 'field', 'shard_size' => 25], $aggregation->getArray());

        $aggregation->addAggregation($termAggregation);
        $aggregation->setField('name');
        $aggregation->setShardSize(200);
        $result = $aggregation->toArray();
        $expected = [
            'sampler' => [
                'field' => 'name',
                'shard_size' => 200,
            ],
            'aggregations' => [
                $termAggregation->getName() => $termAggregation->toArray(),
            ],
        ];
        static::assertEquals($expected, $result);
    }

    /**
     * Tests getArray method without provided shard size.
     */
    public function testGetArrayNoShardSize(): void
    {
        $aggregation = new SamplerAggregation('foo', 'bar');
        static::assertEquals(['field' => 'bar'], $aggregation->getArray());
    }
}
