<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\Aggregation\Pipeline;

use OpenSearchDSL\Aggregation\Pipeline\DerivativeAggregation;

/**
 * Unit test for derivative aggregation.
 *
 * @internal
 */
class DerivativeAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests toArray method.
     */
    public function testToArray(): void
    {
        $aggregation = new DerivativeAggregation('foo', 'foo>bar');
        $aggregation->addParameter('gap_policy', 'skip');

        $expected = [
            'derivative' => [
                'buckets_path' => 'foo>bar',
                'gap_policy' => 'skip',
            ],
        ];

        static::assertEquals($expected, $aggregation->toArray());
    }
}
