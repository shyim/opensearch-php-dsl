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

use OpenSearchDSL\SearchEndpoint\SuggestEndpoint;
use OpenSearchDSL\Suggest\Suggest;

/**
 * @internal
 */
class SuggestEndpointTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests constructor.
     */
    public function testItCanBeInstantiated(): void
    {
        static::assertInstanceOf(SuggestEndpoint::class, new SuggestEndpoint());
    }

    /**
     * Tests if endpoint returns builders.
     */
    public function testEndpointGetter(): void
    {
        $suggestName = 'acme_suggest';
        $text = 'foo';
        $suggest = new Suggest($suggestName, 'text', $text, 'acme');
        $endpoint = new SuggestEndpoint();
        $endpoint->add($suggest, $suggestName);
        $builders = $endpoint->getAll();

        static::assertCount(1, $builders);
        static::assertSame($suggest, $builders[$suggestName]);
    }

    /**
     * Tests endpoint normalization.
     */
    public function testNormalize(): void
    {
        $instance = new SuggestEndpoint();

        $suggest = new Suggest('foo', 'bar', 'acme', 'foo');
        $suggest2 = new Suggest('foo2', 'bar2', 'acme2', 'foo2');
        $instance->add($suggest);
        $instance->add($suggest2);

        static::assertSame(
            array_merge($suggest->toArray(), $suggest2->toArray()),
            $instance->normalize()
        );
    }
}
