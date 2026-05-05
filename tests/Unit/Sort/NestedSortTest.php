<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\Sort;

use OpenSearchDSL\Query\TermLevel\TermQuery;
use OpenSearchDSL\Sort\NestedSort;

/**
 * @internal
 */
class NestedSortTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test for single nested.
     */
    public function testSingle(): void
    {
        $query = new NestedSort('somePath', new TermQuery('somePath.id', 10), null, ['a' => 'b']);
        $expected = [
            'path' => 'somePath',
            'filter' => [
                'term' => [
                    'somePath.id' => 10,
                ],
            ],
            'a' => 'b',
        ];
        $result = $query->toArray();
        static::assertEquals($expected, $result);
    }

    /**
     * Test for single nested, no filter.
     */
    public function testNoFilter(): void
    {
        $query = new NestedSort('somePath');
        $expected = [
            'path' => 'somePath',
        ];
        $result = $query->toArray();
        static::assertEquals($expected, $result);
    }

    /**
     * Test for single nested.
     */
    public function testMultipleNesting(): void
    {
        $query = new NestedSort('somePath', new TermQuery('somePath.id', 10));
        $nestedFilter1 = new NestedSort('secondPath', new TermQuery('secondPath.foo', 'bar'));
        $nestedFilter2 = new NestedSort('thirdPath', new TermQuery('thirdPath.x', 'y'));
        $nestedFilter1->setNestedFilter($nestedFilter2);
        $query->setNestedFilter($nestedFilter1);
        $expected = [
            'path' => 'somePath',
            'filter' => [
                'term' => [
                    'somePath.id' => 10,
                ],
            ],
            'nested' => [
                'path' => 'secondPath',
                'filter' => [
                    'term' => [
                        'secondPath.foo' => 'bar',
                    ],
                ],
                'nested' => [
                    'path' => 'thirdPath',
                    'filter' => [
                        'term' => [
                            'thirdPath.x' => 'y',
                        ],
                    ],
                ],
            ],
        ];
        $result = $query->toArray();
        static::assertEquals($expected, $result);
    }

    public function testSetters(): void
    {
        $sort = new NestedSort('secondPath', new TermQuery('secondPath.foo', 'bar'));
        static::assertInstanceOf(TermQuery::class, $sort->getFilter());
        static::assertNull($sort->getNestedFilter());
        static::assertSame('secondPath', $sort->getPath());
    }
}
