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

use OpenSearchDSL\Aggregation\Bucketing\AutoDateHistogramAggregation;

/**
 * @internal
 */
class AudoDateHistogramAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests agg.
     */
    public function testAutoDateHistogramAggregationSetField(): void
    {
        // Case #0 terms aggregation.
        $aggregation = new AutoDateHistogramAggregation('test_agg', 'test_field');

        $result = [
            'auto_date_histogram' => ['field' => 'test_field'],
        ];

        static::assertEquals($aggregation->toArray(), $result);
        static::assertSame('test_field', $aggregation->getField());
        static::assertSame('test_agg', $aggregation->getName());
        static::assertSame(['field' => 'test_field'], $aggregation->getArray());
    }

    /**
     * Tests setSize method.
     */
    public function testAutoDateHistogramAggregationFormat(): void
    {
        $date = '2020-12-25';
        // Case #1
        $aggregation = new AutoDateHistogramAggregation('test_agg', 'test_field');
        $aggregation->addParameter('format', $date);

        $result = [
            'auto_date_histogram' => [
                'field' => 'test_field',
                'format' => $date,
            ],
        ];

        static::assertEquals($aggregation->toArray(), $result);

        // Case #2
        $aggregation = new AutoDateHistogramAggregation('test_agg', 'test_field', null, $date);

        $result = [
            'auto_date_histogram' => [
                'field' => 'test_field',
                'format' => $date,
            ],
        ];

        static::assertEquals($aggregation->toArray(), $result);
    }

    /**
     * Tests buckets.
     */
    public function testAutoDateHistogramAggregationBuckets(): void
    {
        // Case #1
        $aggregation = new AutoDateHistogramAggregation('test_agg', 'wrong_field');
        $aggregation->setField('test_field');

        $aggregation->addParameter('buckets', 5);

        $result = [
            'auto_date_histogram' => [
                'field' => 'test_field',
                'buckets' => 5,
            ],
        ];

        static::assertEquals($aggregation->toArray(), $result);

        // Case #2
        $aggregation = new AutoDateHistogramAggregation('test_agg', 'wrong_field', 5);
        $aggregation->setField('test_field');

        $result = [
            'auto_date_histogram' => [
                'field' => 'test_field',
                'buckets' => 5,
            ],
        ];

        static::assertEquals($aggregation->toArray(), $result);
        static::assertEquals($aggregation->getArray(), [
            'field' => 'test_field',
        ]);

        $aggregation = new AutoDateHistogramAggregation('test_agg', '');
        static::assertSame([], $aggregation->getArray());
    }

    /**
     * Tests getType method.
     */
    public function testAutoDateHistogramAggregationGetType(): void
    {
        $aggregation = new AutoDateHistogramAggregation('foo', 'bar');
        $result = $aggregation->getType();
        static::assertEquals('auto_date_histogram', $result);
    }
}
