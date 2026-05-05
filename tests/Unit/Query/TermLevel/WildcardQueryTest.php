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

use OpenSearchDSL\Query\TermLevel\WildcardQuery;

/**
 * @internal
 */
class WildcardQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test for query toArray() method.
     */
    public function testToArray(): void
    {
        $query = new WildcardQuery('user', 'ki*y', ['foo' => 'bar']);
        $expectedResult = [
            'wildcard' => [
                'user' => [
                    'value' => 'ki*y',
                    'foo' => 'bar',
                ],
            ],
        ];

        static::assertEquals($expectedResult, $query->toArray());
    }
}
