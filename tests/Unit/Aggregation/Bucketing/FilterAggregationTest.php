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

use OpenSearchDSL\Aggregation\Bucketing\FilterAggregation;
use OpenSearchDSL\Aggregation\Bucketing\HistogramAggregation;
use OpenSearchDSL\Query\Compound\BoolQuery;
use OpenSearchDSL\Query\MatchAllQuery;
use OpenSearchDSL\Query\TermLevel\ExistsQuery;
use OpenSearchDSL\Query\TermLevel\TermQuery;

/**
 * @internal
 */
class FilterAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test for filter aggregation toArray() method.
     *
     * @param FilterAggregation $aggregation
     * @param array $expectedResult
     *
     * @dataProvider getToArrayData
     */
    public function testToArray($aggregation, $expectedResult): void
    {
        static::assertEquals($expectedResult, $aggregation->toArray());
    }

    /**
     * Data provider for testToArray.
     *
     * @return array
     */
    public function getToArrayData()
    {
        $out = [];

        // Case #0 filter aggregation.
        $aggregation = new FilterAggregation('test_agg', new MatchAllQuery());
        $filter = new MatchAllQuery();

        $aggregation->setFilter($filter);

        $result = [
            'filter' => $filter->toArray(),
        ];

        $out[] = [
            $aggregation,
            $result,
        ];

        // Case #1 nested filter aggregation.
        $aggregation = new FilterAggregation('test_agg', new MatchAllQuery());
        $aggregation->setFilter($filter);

        $histogramAgg = new HistogramAggregation('acme', 'bar', 10);
        $aggregation->addAggregation($histogramAgg);

        $result = [
            'filter' => $filter->toArray(),
            'aggregations' => [
                $histogramAgg->getName() => $histogramAgg->toArray(),
            ],
        ];

        $out[] = [
            $aggregation,
            $result,
        ];

        // Case #2 testing bool filter.
        $aggregation = new FilterAggregation('test_agg', new MatchAllQuery());
        $matchAllFilter = new MatchAllQuery();
        $termFilter = new TermQuery('acme', 'foo');
        $boolFilter = new BoolQuery();
        $boolFilter->add($matchAllFilter);
        $boolFilter->add($termFilter);

        $aggregation->setFilter($boolFilter);

        $result = [
            'filter' => $boolFilter->toArray(),
        ];

        $out[] = [
            $aggregation,
            $result,
        ];

        return $out;
    }

    /**
     * Test for setField().
     */
    public function testSetField(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('doesn\'t support `field` parameter');
        $aggregation = new FilterAggregation('test_agg', new MatchAllQuery());
        $aggregation->setField('test_field');
    }

    /**
     * Test for toArray() with setting a filter.
     */
    public function testToArrayWithFilter(): void
    {
        $aggregation = new FilterAggregation('test_agg', new MatchAllQuery());
        $aggregation->setFilter(new ExistsQuery('test'));
        $result = $aggregation->toArray();

        static::assertEquals(
            [
                'filter' => [
                    'exists' => [
                        'field' => 'test',
                    ],
                ],
            ],
            $result
        );
    }

    /**
     * Tests if filter can be passed to constructor.
     */
    public function testConstructorFilter(): void
    {
        $matchAllFilter = new MatchAllQuery();
        $aggregation = new FilterAggregation('test', $matchAllFilter);
        static::assertSame('test', $aggregation->getName());
        static::assertEquals(
            [
                'filter' => $matchAllFilter->toArray(),
            ],
            $aggregation->toArray()
        );

        static::assertSame('test', $aggregation->getName());
        static::assertSame($matchAllFilter, $aggregation->getFilter());
        static::assertEquals(
            $matchAllFilter->toArray(),
            $aggregation->getArray()
        );
    }
}
