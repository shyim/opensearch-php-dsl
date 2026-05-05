<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit;

use OpenSearchDSL\Aggregation\Bucketing\NestedAggregation;
use OpenSearchDSL\Aggregation\Bucketing\TermsAggregation;
use OpenSearchDSL\Highlight\Highlight;
use OpenSearchDSL\InnerHit\NestedInnerHit;
use OpenSearchDSL\Query\Compound\BoolQuery;
use OpenSearchDSL\Query\FullText\MatchQuery;
use OpenSearchDSL\Query\MatchAllQuery;
use OpenSearchDSL\Query\TermLevel\TermsQuery;
use OpenSearchDSL\Search;
use OpenSearchDSL\Sort\FieldSort;
use OpenSearchDSL\Sort\NestedSort;
use OpenSearchDSL\Suggest\Suggest;

/**
 * Test for Search.
 *
 * @internal
 */
class SearchTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests Search constructor.
     */
    public function testItCanBeInstantiated(): void
    {
        static::assertInstanceOf(Search::class, new Search());
    }

    public function testScrollUriParameter(): void
    {
        $search = new Search();
        $search->setScroll('5m');

        static::assertArrayHasKey('scroll', $search->getUriParams());
    }

    public function testInvalidParameter(): void
    {
        static::expectException(\InvalidArgumentException::class);

        $search = new Search();
        $search->addUriParam('test', 'test');
    }

    public function testEndpointDestroy(): void
    {
        $search = new Search();
        $search->addQuery(new MatchAllQuery());
        static::assertArrayHasKey('query', $search->toArray());
        $search->destroyEndpoint('query');
        static::assertArrayNotHasKey('query', $search->toArray());
    }

    public function testPostFilter(): void
    {
        $search = new Search();
        $search->addPostFilter(new MatchAllQuery());
        static::assertArrayHasKey('post_filter', $search->toArray());

        static::assertInstanceOf(BoolQuery::class, $search->getPostFilters());
    }

    public function testToArray(): void
    {
        $search = new Search();
        $search->setSize(5);

        $search->toArray();

        static::assertSame(
            [
                'size' => 5,
            ],
            $search->toArray(),
        );
    }

    public function testAdders(): void
    {
        $search = new Search();

        static::assertSame($search, $search->addAggregation(new TermsAggregation('acme')));
        static::assertSame($search, $search->addQuery(new MatchAllQuery(), BoolQuery::MUST, 'foo'));
        static::assertSame($search, $search->addInnerHit(new NestedInnerHit('foo', 'foo')));
        static::assertSame($search, $search->addPostFilter(new TermsQuery('foo', ['bar']), BoolQuery::MUST, 'foo'));
        static::assertSame($search, $search->addSort(new FieldSort('foo')));
        static::assertSame($search, $search->addSuggest(new Suggest('foo', 'suggest', 'foo_bar', 'bar')));
        static::assertSame($search, $search->addHighlight(new Highlight()));
        static::assertSame($search, $search->addUriParam('q', 'bar'));

        static::assertInstanceOf(TermsAggregation::class, $search->getAggregations()['acme']);
        static::assertInstanceOf(MatchAllQuery::class, $search->getQueries()->getQueries(BoolQuery::MUST)['foo']);
        static::assertInstanceOf(NestedInnerHit::class, $search->getInnerHits()['foo']);
        static::assertInstanceOf(TermsQuery::class, $search->getPostFilters()->getQueries(BoolQuery::MUST)['foo']);
        static::assertInstanceOf(FieldSort::class, \array_values($search->getSorts())[0]);
        static::assertInstanceOf(Suggest::class, \array_values($search->getSuggests())[0]);
        static::assertInstanceOf(Highlight::class, $search->getHighlights());
        static::assertSame('bar', $search->getUriParams()['q']);
    }

    public function testAggregationCanHaveKey(): void
    {
        $search = new Search();

        static::assertSame($search, $search->addAggregation(new TermsAggregation('acme'), 'aggregation-key'));
        static::assertArrayHasKey('aggregation-key', $search->getAggregations());
    }

    public function testSortsCanHaveKey(): void
    {
        $search = new Search();

        static::assertSame($search, $search->addSort(new FieldSort('foo'), 'sort-key'));
        static::assertArrayHasKey('sort-key', $search->getSorts());
    }

    public function testInnerHitCanHaveKey(): void
    {
        $search = new Search();

        static::assertSame($search, $search->addInnerHit(new NestedInnerHit('foo', 'foo'), 'inner-hit-key'));
        static::assertArrayHasKey('inner-hit-key', $search->getInnerHits());
    }

    public function testSuggestCanHaveKey(): void
    {
        $search = new Search();

        static::assertSame($search, $search->addSuggest(new Suggest('foo', 'suggest', 'foo_bar', 'bar'), 'suggest-key'));
        static::assertArrayHasKey('suggest-key', $search->getSuggests());
    }

    public function testGetters(): void
    {
        $search = new Search();

        static::assertSame($search, $search->setDocValueFields([]));
        static::assertSame($search, $search->setFrom(5));
        static::assertSame($search, $search->setTrackTotalHits(true));
        static::assertSame($search, $search->setSize(10));
        static::assertSame($search, $search->setSource(true));
        static::assertSame($search, $search->setStoredFields([]));
        static::assertSame($search, $search->setScriptFields([]));
        static::assertSame($search, $search->setExplain(true));
        static::assertSame($search, $search->setVersion(true));
        static::assertSame($search, $search->setIndicesBoost([]));
        static::assertSame($search, $search->setMinScore(7));
        static::assertSame($search, $search->setSearchAfter([]));
        static::assertSame($search, $search->setScroll(''));

        static::assertSame([], $search->getDocValueFields());
        static::assertSame(5, $search->getFrom());
        static::assertTrue($search->isTrackTotalHits());
        static::assertSame(10, $search->getSize());
        static::assertTrue($search->isSource());
        static::assertTrue($search->getSource());
        static::assertSame([], $search->getStoredFields());
        static::assertSame([], $search->getScriptFields());
        static::assertTrue($search->isExplain());
        static::assertTrue($search->isVersion());
        static::assertSame([], $search->getIndicesBoost());
        static::assertSame(7.0, $search->getMinScore());
        static::assertSame([], $search->getSearchAfter());
        static::assertSame('', $search->getScroll());
    }

    public function testSource(): void
    {
        $search = new Search();

        static::assertTrue($search->isSource());
        $search->setSource(false);
        static::assertFalse($search->isSource());

        $search->setSource(true);
        static::assertTrue($search->isSource());

        $search->setSource('');
        static::assertFalse($search->isSource());
        static::assertSame(['_source' => ''], $search->toArray());

        $search->setSource('test');
        static::assertTrue($search->isSource());
    }

    public function testFrom(): void
    {
        $search = new Search();
        $search->setFrom(10);

        static::assertSame(
            [
                'from' => 10,
            ],
            $search->toArray(),
        );

        $search->setFrom(null);
        static::assertSame([], $search->toArray());
    }

    public function testNestedQuery(): void
    {
        $search = new Search();
        $bool = new BoolQuery();
        $bool->addParameter('minimum_should_match', '2');
        $bool->add(new BoolQuery([
            BoolQuery::MUST => new MatchQuery('foo', 'bar'),
            BoolQuery::SHOULD => new MatchQuery('foo', 'baz'),
            BoolQuery::MUST_NOT => new BoolQuery([
                BoolQuery::MUST => new MatchQuery('foo', 'bar'),
                BoolQuery::SHOULD => new MatchQuery('foo', 'baz'),
            ]),
        ]));

        $search->addQuery($bool);
        $search->addSort(new NestedSort('foo', new FieldSort('foo')));
        $search->addAggregation((new NestedAggregation('foo', 'path'))->addAggregation(new TermsAggregation('acme')));

        static::assertSame(
            [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'match' => [
                                    'foo' => [
                                        'query' => 'bar',
                                    ],
                                ],
                            ],
                        ],
                        'should' => [
                            [
                                'match' => [
                                    'foo' => [
                                        'query' => 'baz',
                                    ],
                                ],
                            ],
                        ],
                        'must_not' => [
                            [
                                'bool' => [
                                    'must' => [
                                        [
                                            'match' => [
                                                'foo' => [
                                                    'query' => 'bar',
                                                ],
                                            ],
                                        ],
                                    ],
                                    'should' => [
                                        [
                                            'match' => [
                                                'foo' => [
                                                    'query' => 'baz',
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'sort' => [
                    [
                        'path' => 'foo',
                        'filter' => [
                            'foo' => [],
                        ],
                    ],
                ],
                'aggregations' => [
                    'foo' => [
                        'nested' => [
                            'path' => 'path',
                        ],
                        'aggregations' => [
                            'acme' => [
                                'terms' => [],
                            ],
                        ],
                    ],
                ],
            ],
            $search->toArray(),
        );
    }
}
