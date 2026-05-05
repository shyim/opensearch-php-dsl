<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\Query\FullText;

use OpenSearchDSL\Query\FullText\CommonTermsQuery;

/**
 * @internal
 */
class CommonTermsQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests toArray().
     */
    public function testToArray(): void
    {
        $query = new CommonTermsQuery('body', 'this is bonsai cool', ['cutoff_frequency' => 0.01]);
        $expected = [
            'common' => [
                'body' => [
                    'query' => 'this is bonsai cool',
                    'cutoff_frequency' => 0.01,
                ],
            ],
        ];

        static::assertEquals($expected, $query->toArray());
    }
}
