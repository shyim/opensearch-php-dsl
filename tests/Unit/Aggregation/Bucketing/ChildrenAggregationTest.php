<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\Aggregation\Bucketing;

use OpenSearchDSL\Aggregation\Bucketing\ChildrenAggregation;
use OpenSearchDSL\Aggregation\Bucketing\TermsAggregation;

/**
 * Unit test for children aggregation.
 *
 * @internal
 */
class ChildrenAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests getType method.
     */
    public function testChildrenAggregationGetType(): void
    {
        $aggregation = new ChildrenAggregation('foo', '');
        $result = $aggregation->getType();
        static::assertEquals('children', $result);
    }

    /**
     * Tests getArray method.
     */
    public function testChildrenAggregationGetArray(): void
    {
        $aggregation = new ChildrenAggregation('foo', '');
        $aggregation->setChildren('question');
        $aggregation->addAggregation(new TermsAggregation('test'));
        $result = $aggregation->getArray();
        $expected = ['type' => 'question'];
        static::assertEquals($expected, $result);
    }

    public function testChildrenThrowsErrorWithoutAgg(): void
    {
        static::expectException(\LogicException::class);

        $aggregation = new ChildrenAggregation('foo', '');
        $aggregation->setChildren('question');
        static::assertSame('question', $aggregation->getChildren());
        $aggregation->toArray();
    }

    public function testChildrenAreSet(): void
    {
        $aggregation = new ChildrenAggregation('foo', 'child');

        static::assertSame('child', $aggregation->getChildren());
    }
}
