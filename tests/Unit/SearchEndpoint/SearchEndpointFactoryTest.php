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

use OpenSearchDSL\SearchEndpoint\AbstractSearchEndpoint;
use OpenSearchDSL\SearchEndpoint\AggregationsEndpoint;
use OpenSearchDSL\SearchEndpoint\SearchEndpointFactory;

/**
 * Unit test class for search endpoint factory.
 *
 * @internal
 */
class SearchEndpointFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests get method exception.
     */
    public function testGet(): void
    {
        $this->expectException(\RuntimeException::class);
        SearchEndpointFactory::get('foo');
    }

    /**
     * Tests if factory can create endpoint.
     */
    public function testFactory(): void
    {
        $endpoint = SearchEndpointFactory::get(AggregationsEndpoint::NAME);
        static::assertInstanceOf(AbstractSearchEndpoint::class, $endpoint);
    }
}
