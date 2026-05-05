<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\Query\Compound;

use OpenSearchDSL\Query\Compound\BoolQuery;
use OpenSearchDSL\Query\MatchAllQuery;
use OpenSearchDSL\Query\TermLevel\TermQuery;

/**
 * Unit test for Bool.
 *
 * @internal
 */
class BoolQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test for addToBool() without setting a correct bool operator.
     */
    public function testBoolAddToBoolException(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('The bool operator acme is not supported');
        $bool = new BoolQuery();
        $bool->add(new MatchAllQuery(), 'acme');
    }

    /**
     * Tests constructor builds container
     */
    public function testBoolConstructor(): void
    {
        $bool = new BoolQuery([
            BoolQuery::SHOULD => [new TermQuery('key1', 'value1')],
            BoolQuery::MUST => [
                new TermQuery('key2', 'value2'),
                new TermQuery('key3', 'value3'),
            ],
            BoolQuery::MUST_NOT => new TermQuery('key4', 'value4'),
        ]);

        $expected = [
            'bool' => [
                'should' => [
                    [
                        'term' => [
                            'key1' => 'value1',
                        ],
                    ],
                ],
                'must' => [
                    [
                        'term' => [
                            'key2' => 'value2',
                        ],
                    ],
                    [
                        'term' => [
                            'key3' => 'value3',
                        ],
                    ],
                ],
                'must_not' => [
                    [
                        'term' => [
                            'key4' => 'value4',
                        ],
                    ],
                ],
            ],
        ];
        static::assertEquals($expected, $bool->toArray());
    }

    /**
     * Tests exception thrown if invalid BoolQuery type key is specified
     */
    public function testBoolConstructorException(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('The bool operator acme is not supported');
        new BoolQuery([
            'acme' => [new TermQuery('key1', 'value1')],
        ]);
    }

    /**
     * Tests toArray() method.
     */
    public function testBoolToArray(): void
    {
        $bool = new BoolQuery();
        $bool->add(new TermQuery('key1', 'value1'), BoolQuery::SHOULD);
        $bool->add(new TermQuery('key2', 'value2'), BoolQuery::MUST);
        $bool->add(new TermQuery('key3', 'value3'), BoolQuery::MUST_NOT);
        $expected = [
            'bool' => [
                'should' => [
                    [
                        'term' => [
                            'key1' => 'value1',
                        ],
                    ],
                ],
                'must' => [
                    [
                        'term' => [
                            'key2' => 'value2',
                        ],
                    ],
                ],
                'must_not' => [
                    [
                        'term' => [
                            'key3' => 'value3',
                        ],
                    ],
                ],
            ],
        ];
        static::assertEquals($expected, $bool->toArray());
    }

    /**
     * Tests bool query with empty body if it forms \stdObject
     */
    public function testEmptyBoolQuery(): void
    {
        $bool = new BoolQuery();

        static::assertEquals(['bool' => new \stdClass()], $bool->toArray());
    }

    /**
     * Tests bool query in filter context.
     */
    public function testBoolInFilterContext(): void
    {
        $bool = new BoolQuery();
        $bool->add(new TermQuery('key1', 'value1'), BoolQuery::FILTER);
        $bool->add(new TermQuery('key2', 'value2'), BoolQuery::MUST);
        $expected = [
            'bool' => [
                'filter' => [
                    [
                        'term' => [
                            'key1' => 'value1',
                        ],
                    ],
                ],
                'must' => [
                    [
                        'term' => [
                            'key2' => 'value2',
                        ],
                    ],
                ],
            ],
        ];
        static::assertEquals($expected, $bool->toArray());
    }

    /**
     * Test if simplified structure is returned in case single MUST query given.
     */
    public function testSingleMust(): void
    {
        $bool = new BoolQuery();
        $bool->add(new TermQuery('key2', 'value2'), BoolQuery::MUST);
        $expected = [
            'term' => [
                'key2' => 'value2',
            ],
        ];
        static::assertEquals($expected, $bool->toArray());
    }

    /**
     * Tests if BoolQuery::getQueries returns an empty array.
     */
    public function testGetQueriesEmpty(): void
    {
        $bool = new BoolQuery();

        static::assertSame([], $bool->getQueries());
    }

    /**
     * Tests if BoolQuery::getQueries returns an array with the added queries of all bool types.
     */
    public function testGetQueries(): void
    {
        $query = new TermQuery('key1', 'value1');
        $query2 = new TermQuery('key2', 'value2');

        $bool = new BoolQuery();
        $bool->add($query, BoolQuery::MUST, 'query');
        $bool->add($query2, BoolQuery::SHOULD, 'query2');

        static::assertSame(['query' => $query, 'query2' => $query2], $bool->getQueries());
    }

    /**
     * Tests if BoolQuery::getQueries with specified bool type returns an empty array.
     */
    public function testGetQueriesByBoolTypeEmpty(): void
    {
        $bool = new BoolQuery();

        static::assertSame([], $bool->getQueries(BoolQuery::MUST));
    }

    /**
     * Tests if BoolQuery::getQueries with specified bool type returns an array with added queries.
     */
    public function testGetQueriesByBoolTypeWithQueryAddedToBoolType(): void
    {
        $query = new TermQuery('key1', 'value1');
        $query2 = new TermQuery('key2', 'value2');

        $bool = new BoolQuery();
        $bool->add($query, BoolQuery::MUST, 'query');
        $bool->add($query2, BoolQuery::SHOULD, 'query2');

        static::assertSame(['query' => $query], $bool->getQueries(BoolQuery::MUST));
    }
}
