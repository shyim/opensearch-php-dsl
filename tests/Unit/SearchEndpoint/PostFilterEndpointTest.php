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
use OpenSearchDSL\SearchEndpoint\PostFilterEndpoint;

/**
 * Class PostFilterEndpointTest.
 *
 * @internal
 */
class PostFilterEndpointTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests constructor.
     */
    public function testItCanBeInstantiated(): void
    {
        static::assertInstanceOf(PostFilterEndpoint::class, new PostFilterEndpoint());
    }

    /**
     * Tests if correct order is returned. It's very important that filters must be executed second.
     */
    public function testGetOrder(): void
    {
        $instance = new PostFilterEndpoint();
        static::assertEquals(1, $instance->getOrder());
    }

    /**
     * Test normalization.
     */
    public function testNormalization(): void
    {
        $instance = new PostFilterEndpoint();
        static::assertNull($instance->normalize());

        $matchAll = new MatchAllQuery();
        $instance->add($matchAll);

        static::assertEquals(
            json_encode($matchAll->toArray(), \JSON_THROW_ON_ERROR),
            json_encode($instance->normalize(), \JSON_THROW_ON_ERROR)
        );
    }

    /**
     * Tests if endpoint returns builders.
     */
    public function testEndpointGetter(): void
    {
        $filterName = 'acme_post_filter';
        $filter = new MatchAllQuery();

        $endpoint = new PostFilterEndpoint();
        $endpoint->add($filter, $filterName);
        $builders = $endpoint->getAll();

        static::assertCount(1, $builders);
        static::assertSame($filter, $builders[$filterName]);
    }
}
