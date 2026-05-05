<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\SearchEndpoint;

/**
 * Factory for search endpoints.
 */
class SearchEndpointFactory
{
    /**
     * @var array holds namespaces for endpoints
     */
    private static array $endpoints = [
        'query' => QueryEndpoint::class,
        'post_filter' => PostFilterEndpoint::class,
        'sort' => SortEndpoint::class,
        'highlight' => HighlightEndpoint::class,
        'aggregations' => AggregationsEndpoint::class,
        'suggest' => SuggestEndpoint::class,
        'inner_hits' => InnerHitsEndpoint::class,
        'collapse' => CollapseEndpoint::class,
    ];

    /**
     * Returns a search endpoint instance.
     *
     * @param string $type type of endpoint
     *
     * @return AbstractSearchEndpoint
     *
     * @throws \RuntimeException endpoint does not exist
     */
    public static function get($type)
    {
        if (!array_key_exists($type, self::$endpoints)) {
            throw new \RuntimeException('Endpoint does not exist.');
        }

        return new self::$endpoints[$type]();
    }
}
