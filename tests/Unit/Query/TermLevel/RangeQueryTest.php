<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\Query\TermLevel;

use OpenSearchDSL\Query\TermLevel\RangeQuery;

/**
 * @internal
 */
class RangeQueryTest extends \PHPUnit\Framework\TestCase
{
    public function testItThrowsExceptionOnDuplicateGTParameter(): void
    {
        $this->expectException(\LogicException::class);

        new RangeQuery(
            'foo',
            [
                RangeQuery::GT => 5,
                RangeQuery::GTE => 6,
            ]
        );
    }

    public function testItThrowsExceptionOnDuplicateLTParameter(): void
    {
        $this->expectException(\LogicException::class);

        new RangeQuery(
            'foo',
            [
                RangeQuery::LT => 6,
                RangeQuery::LTE => 5,
            ]
        );
    }

    /**
     * Tests toArray().
     */
    public function testToArray(): void
    {
        $query = new RangeQuery('age', ['gte' => 10, 'lte' => 20]);
        $expected = [
            'range' => [
                'age' => [
                    'gte' => 10,
                    'lte' => 20,
                ],
            ],
        ];

        static::assertEquals($expected, $query->toArray());
    }
}
