<?php declare(strict_types=1);

namespace OpenSearchDSL\Tests\Unit\Aggregation\Matrix;

use OpenSearchDSL\Aggregation\Matrix\MaxAggregation;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class MaxAggregationTest extends TestCase
{
    public function testConstructor(): void
    {
        $a = new MaxAggregation('test', 'test', ['test'], 'test');
        static::assertSame('test', $a->getName());
        static::assertSame('test', $a->getMode());
        static::assertSame(['test'], $a->getMissing());
        static::assertSame(['test'], $a->getFields());
        static::assertSame([
            'matrix_stats' => [
                'fields' => null,
                'mode' => 'test',
                'missing' => ['test'],
            ],
        ], $a->toArray());

        $a->setFields(['test1', 'test2']);
        static::assertSame(['test1', 'test2'], $a->getFields());

        $a->setMissing(['test1', 'test2']);
        static::assertSame(['test1', 'test2'], $a->getMissing());

        $a->setMode('foo');
        static::assertSame('foo', $a->getMode());
    }
}
