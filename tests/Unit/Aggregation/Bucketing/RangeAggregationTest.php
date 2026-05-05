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

use OpenSearchDSL\Aggregation\Bucketing\RangeAggregation;

/**
 * @internal
 */
class RangeAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test addRange method.
     */
    public function testRangeAggregationAddRange(): void
    {
        $aggregation = new RangeAggregation('test_agg');
        $aggregation->setField('test_field');
        $aggregation->addRange(10, 20);

        $result = [
            'range' => [
                'field' => 'test_field',
                'ranges' => [
                    [
                        'from' => 10,
                        'to' => 20,
                    ],
                ],
                'keyed' => false,
            ],
        ];

        static::assertEquals($result, $aggregation->toArray());
    }

    /**
     * Test addRange method with multiple values.
     */
    public function testRangeAggregationAddRangeMultiple(): void
    {
        $aggregation = new RangeAggregation('test_agg');
        $aggregation->setField('test_field');
        $aggregation->setKeyed(true);
        $aggregation->addRange(10, null, 'range_1');
        $aggregation->addRange(null, 20, 'range_2');

        $result = [
            'range' => [
                'field' => 'test_field',
                'ranges' => [
                    [
                        'from' => 10,
                        'key' => 'range_1',
                    ],
                    [
                        'to' => 20,
                        'key' => 'range_2',
                    ],
                ],
                'keyed' => true,
            ],
        ];

        static::assertEquals($result, $aggregation->toArray());
    }

    /**
     * Test addRange method with nested values.
     */
    public function testRangeAggregationAddRangeNested(): void
    {
        $aggregation = new RangeAggregation('test_agg');
        $aggregation->setField('test_field');
        $aggregation->addRange(10, 10);

        $aggregation2 = new RangeAggregation('test_agg_2');
        $aggregation2->addRange(20, 20);

        $aggregation->addAggregation($aggregation2);

        $result = [
            'range' => [
                'field' => 'test_field',
                'ranges' => [
                    [
                        'from' => 10,
                        'to' => 10,
                    ],
                ],
                'keyed' => false,
            ],
            'aggregations' => [
                'test_agg_2' => [
                    'range' => [
                        'ranges' => [
                            [
                                'from' => 20,
                                'to' => 20,
                            ],
                        ],
                        'keyed' => false,
                    ],
                ],
            ],
        ];

        static::assertEquals($result, $aggregation->toArray());
    }

    /**
     * Tests getType method.
     */
    public function testRangeAggregationGetType(): void
    {
        $agg = new RangeAggregation('foo');
        $result = $agg->getType();
        static::assertEquals('range', $result);
    }

    /**
     * Tests removeRangeByKey method.
     */
    public function testRangeAggregationRemoveRangeByKey(): void
    {
        $aggregation = new RangeAggregation('foo');
        $aggregation->setField('price');
        $aggregation->setKeyed(true);
        $aggregation->addRange(100, 300, 'name');

        $expected = [
            'field' => 'price',
            'keyed' => true,
            'ranges' => [
                [
                    'from' => 100,
                    'to' => 300,
                    'key' => 'name',
                ],
            ],
        ];

        $result = $aggregation->getArray();
        static::assertEquals($result, $expected, 'get array of ranges when keyed=true');

        $result = $aggregation->removeRangeByKey('name');
        static::assertTrue($result, 'returns true when removed valid range name');

        $result = $aggregation->removeRangeByKey('not_existing_key');
        static::assertFalse($result, 'should not allow remove not existing key if keyed=true');

        $aggregation->setKeyed(false);
        $result = $aggregation->removeRangeByKey('not_existing_key');
        static::assertFalse($result, 'should not allow remove not existing key if keyed=false');

        $aggregation->addRange(100, 300, 'name');
        $result = $aggregation->removeRangeByKey('name');
        static::assertFalse($result, 'can not remove any existing range if keyed=false');
    }

    /**
     * Tests removeRange method.
     */
    public function testRangeAggregationRemoveRange(): void
    {
        $aggregation = new RangeAggregation('foo');
        $aggregation->setField('price');
        $aggregation->setKeyed(true);
        $aggregation->addRange(100, 300, 'key');
        $aggregation->addRange(500, 700, 'range_2');

        $expected = [
            'field' => 'price',
            'keyed' => true,
            'ranges' => [
                [
                    'from' => 100,
                    'to' => 300,
                    'key' => 'key',
                ],
            ],
        ];

        $aggregation->removeRange(500, 700);
        $result = $aggregation->getArray();
        static::assertEquals($result, $expected, 'get expected array of ranges');
        $result = $aggregation->removeRange(500, 700);
        static::assertFalse($result, 'returns false after removing not-existing range');

        $aggregation = new RangeAggregation('foo', 'price');
        $aggregation->addRange(500, 200);
        $aggregation->addRange(300, 200);

        $aggregation->removeRange(500, 200);
        $aggregation->removeRange(500, 200);
        static::assertCount(1, $aggregation->getArray()['ranges']);
        static::assertArrayHasKey(0, $aggregation->getArray()['ranges']);
    }

    /**
     * Tests if parameter can be passed to constructor.
     */
    public function testConstructor(): void
    {
        $aggregation = new RangeAggregation('foo', 'fieldValue', [['from' => 10, 'key' => '20']], true);
        static::assertSame(
            [
                'range' => [
                    'keyed' => true,
                    'ranges' => [
                        [
                            'from' => 10.0,
                            'key' => '20',
                        ],
                    ],
                    'field' => 'fieldValue',
                ],
            ],
            $aggregation->toArray()
        );
    }

    public function testRangeInConstructor(): void
    {
        $aggregation = new RangeAggregation('foo', 'fieldValue', [['from' => 10]], true);
        static::assertSame(
            [
                'range' => [
                    'keyed' => true,
                    'ranges' => [
                        [
                            'from' => 10.0,
                        ],
                    ],
                    'field' => 'fieldValue',
                ],
            ],
            $aggregation->toArray()
        );

        $aggregation = new RangeAggregation('foo', 'fieldValue', [['to' => 10]], true);
        static::assertSame(
            [
                'range' => [
                    'keyed' => true,
                    'ranges' => [
                        [
                            'to' => 10.0,
                        ],
                    ],
                    'field' => 'fieldValue',
                ],
            ],
            $aggregation->toArray()
        );

        $aggregation = new RangeAggregation('foo', 'fieldValue', [['key' => '10']], true);
        static::assertSame(
            [
                'range' => [
                    'keyed' => true,
                    'ranges' => [
                        [
                            'key' => '10',
                        ],
                    ],
                    'field' => 'fieldValue',
                ],
            ],
            $aggregation->toArray()
        );

        $aggregation = new RangeAggregation('foo', 'fieldValue', [['key' => '10']], true);
        static::assertFalse($aggregation->removeRangeByKey('5'));
        $aggregation->removeRangeByKey('10');

        static::assertSame(
            [
                'range' => [
                    'keyed' => true,
                    'ranges' => [],
                    'field' => 'fieldValue',
                ],
            ],
            $aggregation->toArray()
        );

        $aggregation->addRange(10, 20, 'key');
        $aggregation->removeRange(10, 20);

        static::assertSame(
            [
                'range' => [
                    'keyed' => true,
                    'ranges' => [],
                    'field' => 'fieldValue',
                ],
            ],
            $aggregation->toArray()
        );

        $aggregation->addRange(10);
        static::assertFalse($aggregation->removeRange(10, 50));
        static::assertFalse($aggregation->removeRange(800, 50));
        static::assertTrue($aggregation->removeRange(10, null));

        static::assertSame(
            [
                'range' => [
                    'keyed' => true,
                    'ranges' => [],
                    'field' => 'fieldValue',
                ],
            ],
            $aggregation->toArray()
        );
    }
}
