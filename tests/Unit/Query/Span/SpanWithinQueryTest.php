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

use OpenSearchDSL\Query\Span\SpanWithinQuery;
use PHPUnit\Framework\MockObject\MockBuilder;

/**
 * Unit test for SpanWithinQuery.
 *
 * @internal
 */
class SpanWithinQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests for toArray().
     */
    public function testToArray(): void
    {
        $query = new SpanWithinQuery(
            $this->getSpanQueryMock('foo'),
            $this->getSpanQueryMock('bar')
        );
        $result = [
            'span_within' => [
                'little' => [
                    'span_term' => ['user' => 'foo'],
                ],
                'big' => [
                    'span_term' => ['user' => 'bar'],
                ],
            ],
        ];
        static::assertEquals($result, $query->toArray());
    }

    /**
     * @param string $value
     *
     * @returns MockBuilder
     */
    private function getSpanQueryMock($value)
    {
        $mock = $this->getMockBuilder(\OpenSearchDSL\Query\Span\SpanQueryInterface::class)->getMock();
        $mock
            ->expects(static::once())
            ->method('toArray')
            ->willReturn(['span_term' => ['user' => $value]])
        ;

        return $mock;
    }
}
