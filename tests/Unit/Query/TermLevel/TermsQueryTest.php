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

use OpenSearchDSL\Query\TermLevel\TermsQuery;

/**
 * @internal
 */
class TermsQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests toArray().
     */
    public function testToArray(): void
    {
        $query = new TermsQuery('user', ['bob', 'elasticsearch'], ['foo' => 'bar']);
        $expected = [
            'terms' => [
                'user' => ['bob', 'elasticsearch'],
                'foo' => 'bar',
            ],
        ];

        static::assertEquals($expected, $query->toArray());
    }
}
