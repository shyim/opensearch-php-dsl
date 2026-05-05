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

use OpenSearchDSL\Aggregation\Bucketing\Ipv4RangeAggregation;

/**
 * @internal
 */
class Ipv4RangeAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests if field and range  can be passed to constructor.
     */
    public function testConstructorFilter(): void
    {
        $aggregation = new Ipv4RangeAggregation('test', 'fieldName', [5 => ['from' => 'fromValue']]);
        static::assertSame('test', $aggregation->getName());
        static::assertSame('fieldName', $aggregation->getField());
        static::assertSame(
            [
                'ip_range' => [
                    'field' => 'fieldName',
                    'ranges' => [['from' => 'fromValue']],
                ],
            ],
            $aggregation->toArray()
        );
        static::assertSame(
            [
                'field' => 'fieldName',
                'ranges' => [['from' => 'fromValue']],
            ],
            $aggregation->getArray()
        );

        $aggregation = new Ipv4RangeAggregation('test', 'fieldName', ['maskValue']);
        static::assertSame(
            [
                'ip_range' => [
                    'field' => 'fieldName',
                    'ranges' => [['mask' => 'maskValue']],
                ],
            ],
            $aggregation->toArray()
        );
    }

    public function testRanges(): void
    {
        $aggregation = new Ipv4RangeAggregation('test', 'fieldName', [['from' => 'fromValue']]);
        static::assertSame(
            [
                'ip_range' => [
                    'field' => 'fieldName',
                    'ranges' => [['from' => 'fromValue']],
                ],
            ],
            $aggregation->toArray()
        );

        $aggregation = new Ipv4RangeAggregation('test', 'fieldName', [['to' => 'toValue']]);
        static::assertSame(
            [
                'ip_range' => [
                    'field' => 'fieldName',
                    'ranges' => [['to' => 'toValue']],
                ],
            ],
            $aggregation->toArray()
        );

        $aggregation = new Ipv4RangeAggregation('test', 'fieldName', [['to' => 'toValue'], ['from' => 'fromValue']]);
        static::assertSame(
            [
                'ip_range' => [
                    'field' => 'fieldName',
                    'ranges' => [['to' => 'toValue'], ['from' => 'fromValue']],
                ],
            ],
            $aggregation->toArray()
        );

        $aggregation = new Ipv4RangeAggregation('test', 'fieldName', []);
        $aggregation->addRange('fromValue');
        $aggregation->addRange('fromValue', 'toValue');
        $aggregation->addRange(null, 'toValue');
        static::assertSame(
            [
                'ip_range' => [
                    'field' => 'fieldName',
                    'ranges' => [['from' => 'fromValue'], ['from' => 'fromValue', 'to' => 'toValue'], ['to' => 'toValue']],
                ],
            ],
            $aggregation->toArray()
        );

        $aggregation->addMask('test');
        static::assertSame(
            [
                'ip_range' => [
                    'field' => 'fieldName',
                    'ranges' => [['from' => 'fromValue'], ['from' => 'fromValue', 'to' => 'toValue'], ['to' => 'toValue'], ['mask' => 'test']],
                ],
            ],
            $aggregation->toArray()
        );

        static::assertSame(
            [
                'field' => 'fieldName',
                'ranges' => [['from' => 'fromValue'], ['from' => 'fromValue', 'to' => 'toValue'], ['to' => 'toValue'], ['mask' => 'test']],
            ],
            $aggregation->getArray()
        );
    }

    public function testWithoutRanges(): void
    {
        static::expectException(\LogicException::class);
        $aggregation = new Ipv4RangeAggregation('test', 'fieldName');
        $aggregation->toArray();
    }
}
