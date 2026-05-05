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

use OpenSearchDSL\Aggregation\Bucketing\MissingAggregation;

/**
 * @internal
 */
class MissingAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test getArray method.
     */
    public function testMissingAggregationGetArray(): void
    {
        $aggregation = new MissingAggregation('foo', '');
        $aggregation->setField('bar');
        $result = $aggregation->getArray();
        static::assertEquals('bar', $result['field']);
    }

    /**
     * Test getType method.
     */
    public function testMissingAggregationGetType(): void
    {
        $aggregation = new MissingAggregation('bar', '');
        $result = $aggregation->getType();
        static::assertEquals('missing', $result);
    }
}
