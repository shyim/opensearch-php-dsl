<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\Aggregation\Metric;

use OpenSearchDSL\Aggregation\Bucketing\TermsAggregation;
use OpenSearchDSL\Aggregation\Metric\CardinalityAggregation;
use PHPUnit\Framework\TestCase;

/**
 * Unit test for cardinality aggregation.
 *
 * @internal
 */
class CardinalityAggregationTest extends TestCase
{
    /**
     * Tests getArray method.
     */
    public function testGetArray(): void
    {
        $aggregation = new CardinalityAggregation('bar');

        $value = $aggregation->getArray();
        static::assertSame([], $value);

        $aggregation->setScript('foo');
        $result = $aggregation->getArray();

        static::assertArrayHasKey('script', $result, 'key=script when script is set');
        static::assertEquals('foo', $result['script'], 'script=foo when scripts name=foo');

        $aggregation->setField('foo');
        $result = $aggregation->getArray();

        static::assertArrayHasKey('field', $result, 'key=field when field is set');
        static::assertEquals('foo', $result['field'], 'field=foo when fields name=foo');

        $aggregation->setPrecisionThreshold(10);
        static::assertSame(10, $aggregation->getPrecisionThreshold());
        $result = $aggregation->getArray();

        static::assertArrayHasKey('precision_threshold', $result, 'key=precision_threshold when is set');
        static::assertEquals(10, $result['precision_threshold'], 'precision_threshold=10 when is set');

        $aggregation->setRehash(true);
        static::assertTrue($aggregation->isRehash());
        $result = $aggregation->getArray();

        static::assertArrayHasKey('rehash', $result, 'key=rehash when rehash is set');
        static::assertTrue($result['rehash'], 'rehash=true when rehash is set to true');
    }

    /**
     * Tests getType method.
     */
    public function testCardinallyAggregationGetType(): void
    {
        $aggregation = new CardinalityAggregation('foo');
        $result = $aggregation->getType();
        static::assertEquals('cardinality', $result);

        $aggregation->addAggregation(new TermsAggregation('bar'));

        static::assertSame(
            ['cardinality' => []],
            $aggregation->toArray()
        );
    }

    public function testCardinallyAggregationWithIdScript(): void
    {
        $idScript = ['id' => 'scriptId', 'params' => ['param' => 'value']];
        $aggregation = new CardinalityAggregation('foo');
        $aggregation->setScript($idScript);

        static::assertSame($idScript, $aggregation->getScript());
        static::assertSame(
            ['cardinality' => ['script' => $idScript]],
            $aggregation->toArray()
        );
    }
}
