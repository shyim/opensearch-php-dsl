<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\Query;

use OpenSearchDSL\Query\MatchAllQuery;
use OpenSearchDSL\Search;

/**
 * @internal
 */
class MatchAllQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests toArray().
     */
    public function testToArrayWhenThereAreNoParams(): void
    {
        $query = new MatchAllQuery();
        static::assertEquals(['match_all' => new \stdClass()], $query->toArray());
    }

    /**
     * Tests toArray().
     */
    public function testToArrayWithParams(): void
    {
        $params = ['boost' => 5];
        $query = new MatchAllQuery($params);
        static::assertEquals(['match_all' => $params], $query->toArray());
    }

    /**
     * Match all test
     */
    public function testMatchAll(): void
    {
        $search = new Search();
        $matchAll = new MatchAllQuery();

        $search->addQuery($matchAll);

        static::assertEquals([
            'query' => [
                'match_all' => [],
            ],
        ], json_decode(json_encode($search->toArray(), \JSON_THROW_ON_ERROR), true, 512, \JSON_THROW_ON_ERROR));
    }
}
