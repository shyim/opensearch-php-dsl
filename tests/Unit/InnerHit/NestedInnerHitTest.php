<?php declare(strict_types=1);

namespace OpenSearchDSL\Tests\Unit\InnerHit;

use OpenSearchDSL\InnerHit\NestedInnerHit;
use OpenSearchDSL\Query\FullText\MatchQuery;
use OpenSearchDSL\Query\Joining\NestedQuery;
use OpenSearchDSL\Search;

/**
 * @internal
 */
class NestedInnerHitTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests toArray() method.
     *
     * @param NestedInnerHit $innerHit
     * @param array $expected
     *
     * @dataProvider getTestToArrayData
     */
    public function testToArray($innerHit, $expected): void
    {
        static::assertEquals($expected, $innerHit->toArray());
    }

    /**
     * Data provider for testToArray().
     *
     * @return array
     */
    public function getTestToArrayData()
    {
        $out = [];

        $matchQuery = new MatchQuery('foo.bar.aux', 'foo');
        $nestedQuery = new NestedQuery('foo.bar', $matchQuery);
        $searchQuery = new Search();
        $searchQuery->addQuery($nestedQuery);

        $matchSearch = new Search();
        $matchSearch->addQuery($matchQuery);

        $innerHit = new NestedInnerHit('acme', 'foo', $searchQuery);
        $emptyInnerHit = new NestedInnerHit('acme', 'foo');

        $nestedInnerHit1 = new NestedInnerHit('aux', 'foo.bar.aux', $matchSearch);
        $nestedInnerHit2 = new NestedInnerHit('lux', 'foo.bar.aux', $matchSearch);
        $searchQuery->addInnerHit($nestedInnerHit1);
        $searchQuery->addInnerHit($nestedInnerHit2);

        $out[] = [
            $emptyInnerHit,
            [
                'path' => [
                    'foo' => new \stdClass(),
                ],
            ],
        ];

        $out[] = [
            $nestedInnerHit1,
            [
                'path' => [
                    'foo.bar.aux' => [
                        'query' => $matchQuery->toArray(),
                    ],
                ],
            ],
        ];

        $out[] = [
            $innerHit,
            [
                'path' => [
                    'foo' => [
                        'query' => $nestedQuery->toArray(),
                        'inner_hits' => [
                            'aux' => [
                                'path' => [
                                    'foo.bar.aux' => [
                                        'query' => $matchQuery->toArray(),
                                    ],
                                ],
                            ],
                            'lux' => [
                                'path' => [
                                    'foo.bar.aux' => [
                                        'query' => $matchQuery->toArray(),
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return $out;
    }

    /**
     * Tests getters and setters for $name, $path and $query
     */
    public function testGettersAndSetters(): void
    {
        $query = new MatchQuery('acme', 'test');
        $search = new Search();
        $search->addQuery($query);

        $hit = new NestedInnerHit('test', 'acme', new Search());
        $hit->setName('foo');
        $hit->setPath('bar');
        $hit->setSearch($search);

        static::assertEquals('foo', $hit->getName());
        static::assertEquals('bar', $hit->getPath());
        static::assertEquals($search, $hit->getSearch());
    }
}
