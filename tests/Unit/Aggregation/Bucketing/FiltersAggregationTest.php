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

use OpenSearchDSL\Aggregation\Bucketing\FiltersAggregation;
use OpenSearchDSL\Query\MatchAllQuery;

/**
 * Unit test for filters aggregation.
 *
 * @internal
 */
class FiltersAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test if exception is thrown when not anonymous filter is without name.
     */
    public function testIfExceptionIsThrown(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('In not anonymous filters, filter name must be set.');
        $mock = $this->getMockBuilder(\OpenSearchDSL\BuilderInterface::class)->getMock();
        $aggregation = new FiltersAggregation('test_agg');
        $aggregation->addFilter($mock);
    }

    /**
     * Test GetArray method.
     */
    public function testFiltersAggregationGetArray(): void
    {
        $mock = $this->getMockBuilder(\OpenSearchDSL\BuilderInterface::class)->getMock();
        $aggregation = new FiltersAggregation('test_agg');
        $aggregation->setAnonymous(true);
        $aggregation->addFilter($mock, 'name');
        $result = $aggregation->getArray();
        static::assertArrayHasKey('filters', $result);
    }

    /**
     * Tests getType method.
     */
    public function testFiltersAggregationGetType(): void
    {
        $aggregation = new FiltersAggregation('foo');
        $result = $aggregation->getType();
        static::assertEquals('filters', $result);
    }

    /**
     * Test for filter aggregation toArray() method.
     */
    public function testToArray(): void
    {
        $aggregation = new FiltersAggregation('test_agg');
        $filter = $this->getMockBuilder(\OpenSearchDSL\BuilderInterface::class)
            ->onlyMethods(['toArray', 'getType'])
            ->getMockForAbstractClass()
        ;
        $filter->expects(static::any())
            ->method('toArray')
            ->willReturn(['test_field' => ['test_value' => 'test']])
        ;

        $aggregation->addFilter($filter, 'first');
        $aggregation->addFilter($filter, 'second');
        $results = $aggregation->toArray();
        $expected = [
            'filters' => [
                'filters' => [
                    'first' => [
                        'test_field' => [
                            'test_value' => 'test',
                        ],
                    ],
                    'second' => [
                        'test_field' => [
                            'test_value' => 'test',
                        ],
                    ],
                ],
            ],
        ];
        static::assertEquals($expected, $results);
    }

    /**
     * Tests if filters can be passed to constructor.
     */
    public function testConstructorFilter(): void
    {
        $builderInterface1 = $this->getMockForAbstractClass(\OpenSearchDSL\BuilderInterface::class);
        $builderInterface2 = $this->getMockForAbstractClass(\OpenSearchDSL\BuilderInterface::class);

        $aggregation = new FiltersAggregation(
            'test',
            [
                'filter1' => $builderInterface1,
                'filter2' => $builderInterface2,
            ]
        );

        static::assertEquals(
            [
                'filters' => [
                    'filters' => [
                        'filter1' => [],
                        'filter2' => [],
                    ],
                ],
            ],
            $aggregation->toArray()
        );

        $aggregation = new FiltersAggregation(
            'test',
            [
                'filter1' => $builderInterface1,
                'filter2' => $builderInterface2,
            ],
            true
        );

        static::assertEquals(
            [
                'filters' => [
                    'filters' => [
                        [],
                        [],
                    ],
                ],
            ],
            $aggregation->toArray()
        );
    }

    public function testFilterWithoutName(): void
    {
        $aggregation = new FiltersAggregation('test_agg');
        static::expectException(\LogicException::class);
        static::expectExceptionMessage('In not anonymous filters, filter name must be set.');
        $aggregation->addFilter(new MatchAllQuery());
    }

    public function testFilterNane(): void
    {
        $aggregation = new FiltersAggregation('test_agg');
        $aggregation->setAnonymous(true);
        $aggregation->addFilter(new MatchAllQuery());
        $aggregation->addFilter(new MatchAllQuery(), 'test');

        static::assertSame('test_agg', $aggregation->getName());
        static::assertEquals(
            [
                'filters' => [
                    'filters' => [
                        [
                            'match_all' => new \stdClass(),
                        ],
                        [
                            'match_all' => new \stdClass(),
                        ],
                    ],
                ],
            ],
            $aggregation->toArray()
        );
    }
}
