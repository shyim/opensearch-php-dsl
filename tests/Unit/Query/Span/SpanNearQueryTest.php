<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\Query\Span;

use OpenSearchDSL\Query\Span\SpanNearQuery;

/**
 * Unit test for SpanNearQuery.
 *
 * @internal
 */
class SpanNearQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests for toArray().
     */
    public function testToArray(): void
    {
        $mock = $this->getMockBuilder(\OpenSearchDSL\Query\Span\SpanQueryInterface::class)->getMock();
        $mock
            ->expects(static::once())
            ->method('toArray')
            ->willReturn(['span_term' => ['key' => 'value']])
        ;

        $query = new SpanNearQuery(5, [$mock], ['in_order' => false]);
        static::assertSame(5, $query->getSlop());

        $result = [
            'span_near' => [
                'clauses' => [
                    0 => [
                        'span_term' => [
                            'key' => 'value',
                        ],
                    ],
                ],
                'slop' => 5,
                'in_order' => false,
            ],
        ];

        static::assertEquals($result, $query->toArray());
    }
}
