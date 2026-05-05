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

use OpenSearchDSL\Aggregation\Bucketing\ReverseNestedAggregation;
use OpenSearchDSL\Aggregation\Bucketing\TermsAggregation;

/**
 * @internal
 */
class ReverseNestedAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test for reverse_nested aggregation toArray() method exception.
     */
    public function testToArray(): void
    {
        $termAggregation = new TermsAggregation('acme');

        $aggregation = new ReverseNestedAggregation('test_nested_agg', 'path');
        static::assertSame('test_nested_agg', $aggregation->getName());
        static::assertSame('path', $aggregation->getPath());
        $aggregation->setPath('test_path');
        $aggregation->addAggregation($termAggregation);

        $expectedResult = [
            'reverse_nested' => ['path' => 'test_path'],
            'aggregations' => [
                $termAggregation->getName() => $termAggregation->toArray(),
            ],
        ];

        static::assertEquals($expectedResult, $aggregation->toArray());

        static::assertSame(['path' => 'test_path'], $aggregation->getArray());
    }

    /**
     * Test for reverse_nested aggregation toArray() without path.
     */
    public function testToArrayNoPath(): void
    {
        $termAggregation = new TermsAggregation('acme');

        $aggregation = new ReverseNestedAggregation('test_nested_agg');
        $aggregation->addAggregation($termAggregation);

        $expectedResult = [
            'reverse_nested' => new \stdClass(),
            'aggregations' => [
                $termAggregation->getName() => $termAggregation->toArray(),
            ],
        ];

        static::assertEquals(
            json_encode($expectedResult, \JSON_THROW_ON_ERROR),
            json_encode($aggregation->toArray(), \JSON_THROW_ON_ERROR)
        );
    }
}
