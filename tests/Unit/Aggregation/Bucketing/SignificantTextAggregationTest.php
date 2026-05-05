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

use OpenSearchDSL\Aggregation\Bucketing\SignificantTextAggregation;

/**
 * Unit test for children aggregation.
 *
 * @internal
 */
class SignificantTextAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests getType method.
     */
    public function testSignificantTextAggregationGetType(): void
    {
        $aggregation = new SignificantTextAggregation('foo');
        $result = $aggregation->getType();
        static::assertEquals('significant_text', $result);
    }

    /**
     * Tests getArray method.
     */
    public function testSignificantTermsAggregationGetArray(): void
    {
        $aggregation = new SignificantTextAggregation('foo', 'title');

        $result = $aggregation->getArray();
        $expected = ['field' => 'title'];
        static::assertEquals($expected, $result);
    }
}
