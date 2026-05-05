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

use OpenSearchDSL\Aggregation\Bucketing\CompositeAggregation;
use OpenSearchDSL\Aggregation\Bucketing\TermsAggregation;

/**
 * @internal
 */
class CompositeAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test for composite aggregation toArray() method exception.
     */
    public function testToArray(): void
    {
        $compositeAgg = new CompositeAggregation('composite_test_agg');
        static::assertSame('composite_test_agg', $compositeAgg->getName());
        $termsAgg = new TermsAggregation('test_term_agg', 'test_field');
        $compositeAgg->addSource($termsAgg);

        $expectedResult = [
            'composite' => [
                'sources' => [
                    [
                        'test_term_agg' => ['terms' => ['field' => 'test_field']],
                    ],
                ],
            ],
        ];

        static::assertEquals($expectedResult, $compositeAgg->toArray());
    }

    /**
     * Test for composite aggregation toArray() method with size and after part.
     */
    public function testToArrayWithSizeAndAfter(): void
    {
        $compositeAgg = new CompositeAggregation('composite_test_agg');
        $termsAgg = new TermsAggregation('test_term_agg', 'test_field');
        $compositeAgg->addSource($termsAgg);
        $compositeAgg->setSize(5);
        $compositeAgg->setAfter(['test_term_agg' => 'test']);

        $expectedResult = [
            'composite' => [
                'sources' => [
                    [
                        'test_term_agg' => ['terms' => ['field' => 'test_field']],
                    ],
                ],
                'size' => 5,
                'after' => ['test_term_agg' => 'test'],
            ],
        ];

        static::assertEquals($expectedResult, $compositeAgg->toArray());
    }

    /**
     * Test for composite aggregation getSize() method.
     */
    public function testGetSize(): void
    {
        $compositeAgg = new CompositeAggregation('composite_test_agg');
        $compositeAgg->setSize(5);

        static::assertEquals(5, $compositeAgg->getSize());
    }

    /**
     * Test for composite aggregation getAfter() method.
     */
    public function testGetAfter(): void
    {
        $compositeAgg = new CompositeAggregation('composite_test_agg');
        $compositeAgg->setAfter(['test_term_agg' => 'test']);

        static::assertEquals(['test_term_agg' => 'test'], $compositeAgg->getAfter());
    }

    /**
     * Tests getType method.
     */
    public function testGetType(): void
    {
        $aggregation = new CompositeAggregation('foo');
        $result = $aggregation->getType();
        static::assertEquals('composite', $result);
    }

    public function testTermsSourceWithOrderParameter(): void
    {
        $compositeAgg = new CompositeAggregation('composite_with_order');
        $termsAgg = new TermsAggregation('test_term_agg', 'test_field');
        $termsAgg->addParameter('order', 'asc');
        $compositeAgg->addSource($termsAgg);

        $expectedResult = [
            'composite' => [
                'sources' => [
                    [
                        'test_term_agg' => ['terms' => ['field' => 'test_field', 'order' => 'asc']],
                    ],
                ],
            ],
        ];

        static::assertEquals($expectedResult, $compositeAgg->toArray());
    }

    public function testTermsSourceWithDescOrderParameter(): void
    {
        $compositeAgg = new CompositeAggregation('composite_with_order');
        $termsAgg = new TermsAggregation('test_term_agg', 'test_field');
        $termsAgg->addParameter('order', 'desc');
        $compositeAgg->addSource($termsAgg);

        $expectedResult = [
            'composite' => [
                'sources' => [
                    [
                        'test_term_agg' => ['terms' => ['field' => 'test_field', 'order' => 'desc']],
                    ],
                ],
            ],
        ];

        static::assertEquals($expectedResult, $compositeAgg->toArray());
    }

    public function testMultipleSourcesWithDifferentOrders(): void
    {
        $compositeAgg = new CompositeAggregation('composite_with_order');

        $termsAgg = new TermsAggregation('test_term_agg_1', 'test_field');
        $termsAgg->addParameter('order', 'desc');
        $compositeAgg->addSource($termsAgg);

        $termsAgg = new TermsAggregation('test_term_agg_2', 'test_field');
        $termsAgg->addParameter('order', 'asc');
        $compositeAgg->addSource($termsAgg);

        $expectedResult = [
            'composite' => [
                'sources' => [
                    [
                        'test_term_agg_1' => ['terms' => ['field' => 'test_field', 'order' => 'desc']],
                    ],
                    [
                        'test_term_agg_2' => ['terms' => ['field' => 'test_field', 'order' => 'asc']],
                    ],
                ],
            ],
        ];

        static::assertEquals($expectedResult, $compositeAgg->toArray());
    }

    public function testConstructorSourcesWorks(): void
    {
        $compositeAgg = new CompositeAggregation('composite_with_order', [new TermsAggregation('test', 'test')]);
        static::assertCount(1, $compositeAgg->getSources());

        static::assertSame([
            [
                'test' => [
                    'terms' => [
                        'field' => 'test',
                    ],
                ],
            ],
        ], $compositeAgg->getSources());

        $compositeAgg->setSources([]);
        static::assertCount(0, $compositeAgg->getSources());
    }

    public function testSourceGetsMerged(): void
    {
        $compositeAgg = new CompositeAggregation('composite_with_order', []);
        $agg = new TermsAggregation('test_2', 'test_2');
        $agg->setParameters(['foo' => 'bar']);
        $compositeAgg->addSource($agg);

        static::assertSame([
            [
                'test_2' => [
                    'terms' => [
                        'field' => 'test_2',
                        'foo' => 'bar',
                    ],
                ],
            ],
        ], $compositeAgg->getSources());

        static::assertSame([
            'sources' => [
                [
                    'test_2' => [
                        'terms' => [
                            'field' => 'test_2',
                            'foo' => 'bar',
                        ],
                    ],
                ],
            ],
        ], $compositeAgg->getArray());
    }
}
