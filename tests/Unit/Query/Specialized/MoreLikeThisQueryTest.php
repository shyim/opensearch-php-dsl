<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\Query\Specialized;

use OpenSearchDSL\Query\Specialized\MoreLikeThisQuery;

/**
 * @internal
 */
class MoreLikeThisQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests toArray().
     */
    public function testToArray(): void
    {
        $query = new MoreLikeThisQuery('this is a test', ['fields' => ['title', 'description']]);
        $expected = [
            'more_like_this' => [
                'fields' => ['title', 'description'],
                'like' => 'this is a test',
            ],
        ];

        static::assertEquals($expected, $query->toArray());
    }

    /**
     * @dataProvider providerLike
     */
    public function testLike(array $params, array $expected): void
    {
        $query = new MoreLikeThisQuery('this is a test', $params);

        static::assertSame(
            [
                'more_like_this' => $expected,
            ],
            $query->toArray()
        );
    }

    public function providerLike(): iterable
    {
        yield 'with ids' => [
            ['ids' => 'foo'],
            [
                'like' => 'this is a test',
                'ids' => 'foo',
            ],
        ];

        yield 'with ids and docs false' => [
            ['ids' => 'foo', 'docs' => false],
            [
                'ids' => 'foo',
                'docs' => false,
            ],
        ];

        yield 'with ids and docs true' => [
            ['ids' => 'foo', 'docs' => true],
            [
                'ids' => 'foo',
                'docs' => true,
            ],
        ];

        yield 'without ids and docs false' => [
            ['docs' => false],
            [
                'like' => 'this is a test',
                'docs' => false,
            ],
        ];
    }
}
