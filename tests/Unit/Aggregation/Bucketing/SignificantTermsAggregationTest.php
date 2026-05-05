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

use OpenSearchDSL\Aggregation\Bucketing\SignificantTermsAggregation;

/**
 * Unit test for children aggregation.
 *
 * @internal
 */
class SignificantTermsAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests getType method.
     */
    public function testSignificantTermsAggregationGetType(): void
    {
        $aggregation = new SignificantTermsAggregation('foo');
        $result = $aggregation->getType();
        static::assertEquals('significant_terms', $result);
    }

    /**
     * Tests getArray method.
     */
    public function testSignificantTermsAggregationGetArray(): void
    {
        $aggregation = new SignificantTermsAggregation('foo', 'title');
        $result = $aggregation->getArray();
        $expected = ['field' => 'title'];
        static::assertEquals($expected, $result);
    }
}
