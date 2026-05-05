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

use OpenSearchDSL\Query\TermLevel\TermQuery;

/**
 * @internal
 */
class TermQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests toArray().
     */
    public function testToArray(): void
    {
        $query = new TermQuery('user', 'bob');
        $expected = [
            'term' => [
                'user' => 'bob',
            ],
        ];

        static::assertEquals($expected, $query->toArray());
    }
}
