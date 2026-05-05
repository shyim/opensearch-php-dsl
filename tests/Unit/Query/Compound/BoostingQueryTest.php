<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\Query\Compound;

use OpenSearchDSL\Query\Compound\BoostingQuery;

/**
 * @internal
 */
class BoostingQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests toArray().
     */
    public function testToArray(): void
    {
        $mock = $this->getMockBuilder(\OpenSearchDSL\BuilderInterface::class)->getMock();
        $mock
            ->expects(static::any())
            ->method('toArray')
            ->willReturn(['term' => ['foo' => 'bar']])
        ;

        $query = new BoostingQuery($mock, $mock, 0.2);
        $expected = [
            'boosting' => [
                'positive' => ['term' => ['foo' => 'bar']],
                'negative' => ['term' => ['foo' => 'bar']],
                'negative_boost' => 0.2,
            ],
        ];

        static::assertEquals($expected, $query->toArray());
    }
}
