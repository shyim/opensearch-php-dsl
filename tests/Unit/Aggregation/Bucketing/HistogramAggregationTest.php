<?php declare(strict_types=1);

namespace OpenSearchDSL\Tests\Unit\Aggregation\Bucketing;

use OpenSearchDSL\Aggregation\Bucketing\HistogramAggregation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class HistogramAggregationTest extends TestCase
{
    public function testConstructor(): void
    {
        $a = new HistogramAggregation(
            'test',
            'test',
            5,
            2,
            'test',
            HistogramAggregation::DIRECTION_ASC,
            5,
            6,
            false
        );

        static::assertSame('test', $a->getName());
        static::assertSame('test', $a->getField());
        static::assertSame(5, $a->getInterval());
        static::assertSame(2, $a->getMinDocCount());
        static::assertSame(['test' => 'asc'], $a->getOrder());
        static::assertSame(['test' => HistogramAggregation::DIRECTION_ASC], $a->getOrder());
        static::assertSame(['min' => 5, 'max' => 6], $a->getExtendedBounds());
        static::assertFalse($a->isKeyed());

        $a->setKeyed(true);
        static::assertTrue($a->isKeyed());

        $a->setMinDocCount(10);
        static::assertSame(10, $a->getMinDocCount());

        $a->setInterval(10);
        static::assertSame(10, $a->getInterval());

        $a->setField('test2');
        static::assertSame('test2', $a->getField());

        $a->setOrder('test', HistogramAggregation::DIRECTION_DESC);
        static::assertSame(['test' => HistogramAggregation::DIRECTION_DESC], $a->getOrder());

        $a->setExtendedBounds(10, 20);
        static::assertSame(['min' => 10, 'max' => 20], $a->getExtendedBounds());

        $a->setExtendedBounds(null, 20);
        static::assertSame(['max' => 20], $a->getExtendedBounds());

        $a->setOrder('bla', null);
        static::assertNull($a->getOrder());

        static::assertSame([
            'field' => 'test2',
            'interval' => 10,
            'min_doc_count' => 10,
            'extended_bounds' => [
                'max' => 20,
            ],
            'keyed' => true,
        ], $a->getArray());

        $a->setKeyed(null);

        static::assertSame([
            'field' => 'test2',
            'interval' => 10,
            'min_doc_count' => 10,
            'extended_bounds' => [
                'max' => 20,
            ],
        ], $a->getArray());
    }
}
