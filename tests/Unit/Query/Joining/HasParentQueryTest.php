<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\Query\Joining;

use OpenSearchDSL\Query\Joining\HasParentQuery;
use OpenSearchDSL\Query\TermLevel\TermsQuery;

/**
 * @internal
 */
class HasParentQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests whether __constructor calls setParameters method.
     */
    public function testConstructor(): void
    {
        $parentQuery = $this->getMockBuilder(\OpenSearchDSL\BuilderInterface::class)->getMock();
        $query = new HasParentQuery('test_type', $parentQuery, ['test_parameter1']);
        static::assertEquals(['test_parameter1'], $query->getParameters());
    }

    public function testToArray(): void
    {
        $query = new TermsQuery('foo', ['bar']);
        $parentQuery = new HasParentQuery('parent_type', $query, ['foo' => 'bar']);

        static::assertSame(
            [
                'has_parent' => [
                    'parent_type' => 'parent_type',
                    'query' => [
                        'terms' => [
                            'foo' => ['bar'],
                        ],
                    ],
                    'foo' => 'bar',
                ],
            ],
            $parentQuery->toArray(),
        );
    }
}
