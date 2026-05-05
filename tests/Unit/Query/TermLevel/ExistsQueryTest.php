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

use OpenSearchDSL\Query\TermLevel\ExistsQuery;

/**
 * Unit test for ExistsQuery.
 *
 * @internal
 */
class ExistsQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests toArray() method.
     */
    public function testToArray(): void
    {
        $query = new ExistsQuery('bar');
        static::assertEquals(['exists' => ['field' => 'bar']], $query->toArray());
    }
}
