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

use OpenSearchDSL\Highlight\Highlight;
use OpenSearchDSL\Search;
use OpenSearchDSL\SearchEndpoint\HighlightEndpoint;

/**
 * Class HighlightEndpointTest.
 *
 * @internal
 */
class HighlightEndpointTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests adding builder.
     */
    public function testNormalization(): void
    {
        $instance = new HighlightEndpoint();

        static::assertNull($instance->normalize());

        $highlight = new Highlight();
        $highlight->addField('acme');
        $instance->add($highlight);

        static::assertEquals(
            json_encode($highlight->toArray(), \JSON_THROW_ON_ERROR),
            json_encode($instance->normalize(), \JSON_THROW_ON_ERROR)
        );
    }

    /**
     * Tests if endpoint returns builders.
     */
    public function testEndpointGetter(): void
    {
        $highlight = new Highlight();
        $highlight->addField('acme');

        $endpoint = new HighlightEndpoint();
        $endpoint->add($highlight);
        $builders = $endpoint->getAll();

        static::assertCount(1, $builders);
        static::assertSame($highlight, $builders['']);
    }

    public function testItThrowsExceptionOnDuplicateHighlights(): void
    {
        $highlight = new Highlight();

        $endpoint = new HighlightEndpoint();
        $endpoint->add($highlight, 'foo');

        $this->expectException(\OverflowException::class);

        $endpoint->add($highlight, 'bar');
    }

    public function testHighlightSearch(): void
    {
        $search = new Search();

        $highlight = new Highlight();
        $highlight->addField('acme');

        $search->addHighlight($highlight);

        static::assertEquals([
            'highlight' => [
                'fields' => [
                    'acme' => new \stdClass(),
                ],
            ],
        ],
            $search->toArray()
        );
    }
}
