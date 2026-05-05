<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\SearchEndpoint;

use OpenSearchDSL\Query\MatchAllQuery;
use OpenSearchDSL\Query\TermLevel\TermsQuery;
use OpenSearchDSL\SearchEndpoint\QueryEndpoint;

/**
 * Unit test class for the QueryEndpoint.
 *
 * @internal
 */
class QueryEndpointTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests constructor.
     */
    public function testItCanBeInstantiated(): void
    {
        static::assertInstanceOf(QueryEndpoint::class, new QueryEndpoint());
    }

    /**
     * Tests if correct order is returned. Query must be executed after filter and post filter.
     */
    public function testGetOrder(): void
    {
        $instance = new QueryEndpoint();
        static::assertEquals(2, $instance->getOrder());
    }

    /**
     * Tests if endpoint return correct normalized data.
     */
    public function testEndpoint(): void
    {
        $instance = new QueryEndpoint();

        static::assertNull($instance->normalize());

        $matchAll = new MatchAllQuery();
        $instance->add($matchAll);

        static::assertEquals(
            $matchAll->toArray(),
            $instance->normalize()
        );
    }

    /**
     * Tests if endpoint returns builders.
     */
    public function testEndpointGetter(): void
    {
        $queryName = 'acme_query';
        $query = new MatchAllQuery();
        $endpoint = new QueryEndpoint();
        $endpoint->add($query, $queryName);
        $builders = $endpoint->getAll();

        static::assertCount(1, $builders);
        static::assertSame($query, $builders[$queryName]);
    }

    public function testSearchForFilterQueryReference(): void
    {
        $instance = new QueryEndpoint();
        $instance->add(new TermsQuery('foo', ['bla']));

        $instance->addReference('filter_query', new TermsQuery('foo', ['bar']));

        static::assertSame(
            [
                'bool' => [
                    'must' => [[
                        'terms' => [
                            'foo' => [
                                'bla',
                            ],
                        ],
                    ],
                    ],
                    'filter' => [
                        [
                            'terms' => [
                                'foo' => [
                                    'bar',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            $instance->normalize()
        );

        static::assertSame(
            [
                'bool' => [
                    'must' => [[
                        'terms' => [
                            'foo' => [
                                'bla',
                            ],
                        ],
                    ],
                    ],
                    'filter' => [
                        [
                            'terms' => [
                                'foo' => [
                                    'bar',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            $instance->normalize()
        );
    }
}
