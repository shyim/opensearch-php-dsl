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

use OpenSearchDSL\Query\Span\SpanOrQuery;

/**
 * Unit test for SpanOrQuery.
 *
 * @internal
 */
class SpanOrQueryTest extends \PHPUnit\Framework\TestCase
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

        $query = new SpanOrQuery([$mock]);
        $result = [
            'span_or' => [
                'clauses' => [
                    0 => [
                        'span_term' => ['key' => 'value'],
                    ],
                ],
            ],
        ];
        static::assertEquals($result, $query->toArray());

        $result = $query->getQueries();
        static::assertEquals(1, count($result));
    }
}
