<?php declare(strict_types=1);

namespace OpenSearchDSL\Tests\Unit\Serializer;

use OpenSearchDSL\Query\MatchAllQuery;
use OpenSearchDSL\Query\TermLevel\TermsQuery;
use OpenSearchDSL\Search;
use OpenSearchDSL\SearchEndpoint\AggregationsEndpoint;
use OpenSearchDSL\SearchEndpoint\HighlightEndpoint;
use OpenSearchDSL\SearchEndpoint\PostFilterEndpoint;
use OpenSearchDSL\SearchEndpoint\QueryEndpoint;
use OpenSearchDSL\Serializer\OrderedSerializer;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class OrderedSerializerTest extends TestCase
{
    public function testOrdering(): void
    {
        $search = new Search();
        $search->addQuery(new MatchAllQuery());
        $search->addPostFilter(new TermsQuery('foo', ['bar']));

        static::assertEquals(
            [
                [
                    'terms' => [
                        'foo' => ['bar'],
                    ],
                ],
                [
                    'match_all' => new \stdClass(),
                ],
            ],
            OrderedSerializer::normalize(
                [
                    $search->getEndpoint(QueryEndpoint::NAME),
                    $search->getEndpoint(PostFilterEndpoint::NAME),
                ]
            )
        );
    }

    public function testNullOrEmptyArrayFieldGetsDropped(): void
    {
        $search = new Search();

        static::assertSame(
            [],
            OrderedSerializer::normalize(
                [
                    $search->getEndpoint(HighlightEndpoint::NAME),
                    $search->getEndpoint(AggregationsEndpoint::NAME),
                ]
            )
        );
    }

    public function testNonObjects(): void
    {
        static::assertSame(
            [
                'test' => 'string',
                'test1' => 1,
                'test2' => 1.5,
            ],
            OrderedSerializer::normalize(
                [
                    'test' => 'string',
                    'test1' => 1,
                    'test2' => 1.5,
                ]
            )
        );
    }
}
