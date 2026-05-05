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

use OpenSearchDSL\Aggregation\Pipeline\PercentilesBucketAggregation;

/**
 * Unit test for percentiles bucket aggregation.
 *
 * @internal
 */
class PercentilesBucketAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests toArray method.
     */
    public function testToArray(): void
    {
        $aggregation = new PercentilesBucketAggregation('acme', 'test');
        $aggregation->setPercents([25.0, 50.0, 75.0]);
        static::assertSame([25.0, 50.0, 75.0], $aggregation->getPercents());

        $expected = [
            'percentiles_bucket' => [
                'buckets_path' => 'test',
                'percents' => [25.0, 50.0, 75.0],
            ],
        ];

        static::assertEquals($expected, $aggregation->toArray());
    }
}
