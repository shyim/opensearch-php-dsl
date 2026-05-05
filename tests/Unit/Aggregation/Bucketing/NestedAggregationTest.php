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

use OpenSearchDSL\Aggregation\Bucketing\NestedAggregation;
use OpenSearchDSL\Aggregation\Bucketing\TermsAggregation;

/**
 * @internal
 */
class NestedAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test for nested aggregation toArray() method exception.
     */
    public function testToArray(): void
    {
        $termAggregation = new TermsAggregation('acme');

        $aggregation = new NestedAggregation('test_nested_agg', 'test_nested_agg_path');
        static::assertSame('test_nested_agg_path', $aggregation->getPath());
        $aggregation->setPath('test_path');
        $aggregation->addAggregation($termAggregation);

        $expectedResult = [
            'nested' => ['path' => 'test_path'],
            'aggregations' => [
                $termAggregation->getName() => $termAggregation->toArray(),
            ],
        ];

        static::assertEquals($expectedResult, $aggregation->toArray());
        static::assertSame('test_nested_agg', $aggregation->getName());
        static::assertSame('test_path', $aggregation->getPath());

        $aggregation->setPath('test_path_2');
        static::assertSame('test_path_2', $aggregation->getPath());

        static::assertSame([
            'path' => 'test_path_2',
        ], $aggregation->getArray());
    }
}
