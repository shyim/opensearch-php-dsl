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

use OpenSearchDSL\SearchEndpoint\InnerHitsEndpoint;

/**
 * Class AggregationsEndpointTest.
 *
 * @internal
 */
class InnerHitsEndpointTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests constructor.
     */
    public function testItCanBeInstantiated(): void
    {
        static::assertInstanceOf(
            InnerHitsEndpoint::class,
            new InnerHitsEndpoint()
        );
    }

    /**
     * Tests if endpoint returns builders.
     */
    public function testEndpointGetter(): void
    {
        $hitName = 'foo';
        $innerHit = $this->getMockBuilder(\OpenSearchDSL\BuilderInterface::class)->getMock();
        $endpoint = new InnerHitsEndpoint();
        $endpoint->add($innerHit, $hitName);
        $builders = $endpoint->getAll();

        static::assertCount(1, $builders);
        static::assertSame($innerHit, $builders[$hitName]);
    }

    /**
     * Tests normalize method
     */
    public function testNormalization(): void
    {
        $innerHit = $this
            ->getMockBuilder(\OpenSearchDSL\BuilderInterface::class)
            ->onlyMethods(['toArray', 'getType'])
            ->addMethods(['getName'])
            ->getMock()
        ;
        $innerHit->expects(static::any())->method('getName')->willReturn('foo');
        $innerHit->expects(static::any())->method('toArray')->willReturn(['foo' => 'bar']);

        $endpoint = new InnerHitsEndpoint();
        $endpoint->add($innerHit, 'foo');
        $expected = [
            'foo' => [
                'foo' => 'bar',
            ],
        ];

        static::assertEquals(
            $expected,
            $endpoint->normalize()
        );
    }

    public function testEmptyHits(): void
    {
        $endpoint = new InnerHitsEndpoint();

        static::assertEquals([], $endpoint->getAll());
    }
}
