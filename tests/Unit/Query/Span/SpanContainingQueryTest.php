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

use OpenSearchDSL\Query\Span\SpanContainingQuery;
use PHPUnit\Framework\MockObject\MockBuilder;

/**
 * Unit test for SpanContainingQuery.
 *
 * @internal
 */
class SpanContainingQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests for toArray().
     */
    public function testToArray(): void
    {
        $little = $this->getSpanQueryMock('foo');
        $big = $this->getSpanQueryMock('bar');
        $query = new SpanContainingQuery(
            $little,
            $big
        );
        $result = [
            'span_containing' => [
                'little' => [
                    'span_term' => ['user' => 'foo'],
                ],
                'big' => [
                    'span_term' => ['user' => 'bar'],
                ],
            ],
        ];
        static::assertEquals($result, $query->toArray());

        static::assertSame($little, $query->getLittle());
        static::assertSame($big, $query->getBig());
        static::assertSame($query, $query->setLittle($little));
        static::assertSame($query, $query->setBig($big));
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
