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

use OpenSearchDSL\Aggregation\Pipeline\MovingFunctionAggregation;

/**
 * Unit test for sum bucket aggregation.
 *
 * @internal
 */
class MovingFunctionAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests toArray method.
     */
    public function testToArray(): void
    {
        $aggregation = new MovingFunctionAggregation('acme', 'test');

        $expected = [
            'moving_fn' => [
                'buckets_path' => 'test',
            ],
        ];

        static::assertEquals($expected, $aggregation->toArray());
    }
}
