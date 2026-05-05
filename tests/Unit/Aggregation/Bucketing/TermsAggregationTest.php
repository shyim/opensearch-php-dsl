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

use OpenSearchDSL\Aggregation\Bucketing\TermsAggregation;

/**
 * @internal
 */
class TermsAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests setField method.
     */
    public function testTermsAggregationSetField(): void
    {
        // Case #0 terms aggregation.
        $aggregation = new TermsAggregation('test_agg');
        $aggregation->setField('test_field');

        $result = [
            'terms' => ['field' => 'test_field'],
        ];

        static::assertEquals($aggregation->toArray(), $result);
    }

    /**
     * Tests setSize method.
     */
    public function testTermsAggregationSetSize(): void
    {
        // Case #1 terms aggregation with size.
        $aggregation = new TermsAggregation('test_agg');
        $aggregation->setField('test_field');
        $aggregation->addParameter('size', 1);

        $result = [
            'terms' => [
                'field' => 'test_field',
                'size' => 1,
            ],
        ];

        static::assertEquals($aggregation->toArray(), $result);

        // Case #2 terms aggregation with zero size.
        $aggregation = new TermsAggregation('test_agg');
        $aggregation->setField('test_field');
        $aggregation->addParameter('size', 0);

        $result = [
            'terms' => [
                'field' => 'test_field',
                'size' => 0,
            ],
        ];

        static::assertEquals($aggregation->toArray(), $result);
    }

    /**
     * Tests minDocumentCount method.
     */
    public function testTermsAggregationMinDocumentCount(): void
    {
        // Case #3 terms aggregation with size and min document count.
        $aggregation = new TermsAggregation('test_agg');
        $aggregation->setField('test_field');
        $aggregation->addParameter('size', 1);
        $aggregation->addParameter('min_doc_count', 10);

        $result = [
            'terms' => [
                'field' => 'test_field',
                'size' => 1,
                'min_doc_count' => 10,
            ],
        ];

        static::assertEquals($aggregation->toArray(), $result);
    }

    /**
     * Tests include, exclude method.
     */
    public function testTermsAggregationSimpleIncludeExclude(): void
    {
        // Case #4 terms aggregation with simple include, exclude.
        $aggregation = new TermsAggregation('test_agg');
        $aggregation->setField('test_field');
        $aggregation->addParameter('include', 'test_.*');
        $aggregation->addParameter('exclude', 'pizza_.*');

        $result = [
            'terms' => [
                'field' => 'test_field',
                'include' => 'test_.*',
                'exclude' => 'pizza_.*',
            ],
        ];

        static::assertEquals($aggregation->toArray(), $result);
    }

    /**
     * Tests include, exclude with flags method.
     */
    public function testTermsAggregationIncludeExcludeFlags(): void
    {
        // Case #5 terms aggregation with include, exclude and flags.
        $aggregation = new TermsAggregation('test_agg');
        $aggregation->setField('test_field');
        $aggregation->addParameter(
            'include',
            [
                'pattern' => 'test_.*',
                'flags' => 'CANON_EQ|CASE_INSENSITIVE',
            ]
        );
        $aggregation->addParameter(
            'exclude',
            [
                'pattern' => 'pizza_.*',
                'flags' => 'CASE_INSENSITIVE',
            ]
        );

        $result = [
            'terms' => [
                'field' => 'test_field',
                'include' => [
                    'pattern' => 'test_.*',
                    'flags' => 'CANON_EQ|CASE_INSENSITIVE',
                ],
                'exclude' => [
                    'pattern' => 'pizza_.*',
                    'flags' => 'CASE_INSENSITIVE',
                ],
            ],
        ];

        static::assertEquals($aggregation->toArray(), $result);
    }

    /**
     * Tests setOrder method.
     */
    public function testTermsAggregationSetOrder(): void
    {
        // Case #6 terms aggregation with order default direction.
        $aggregation = new TermsAggregation('test_agg');
        $aggregation->setField('test_field');
        $aggregation->addParameter('order', ['_count' => 'asc']);

        $result = [
            'terms' => [
                'field' => 'test_field',
                'order' => ['_count' => 'asc'],
            ],
        ];

        static::assertEquals($aggregation->toArray(), $result);
    }

    /**
     * Tests setOrder DESC method.
     */
    public function testTermsAggregationSetOrderDESC(): void
    {
        // Case #7 terms aggregation with order term mode, desc direction.
        $aggregation = new TermsAggregation('test_agg');
        $aggregation->setField('test_field');
        $aggregation->addParameter('order', ['_term' => 'desc']);

        $result = [
            'terms' => [
                'field' => 'test_field',
                'order' => ['_term' => 'desc'],
            ],
        ];

        static::assertEquals($aggregation->toArray(), $result);
    }

    /**
     * Tests getType method.
     */
    public function testTermsAggregationGetType(): void
    {
        $aggregation = new TermsAggregation('foo');
        $result = $aggregation->getType();
        static::assertEquals('terms', $result);
    }

    public function testTermsAggregationSetScript(): void
    {
        $aggregation = new TermsAggregation('test_agg', 'test_field', 'test_script');
        static::assertSame('test_script', $aggregation->getScript());
        static::assertSame('test_agg', $aggregation->getName());
        static::assertSame('test_field', $aggregation->getField());

        static::assertEquals([
            'terms' => [
                'field' => 'test_field',
                'script' => 'test_script',
            ],
        ], $aggregation->toArray());
    }

    public function testTermsAggregationIdScript(): void
    {
        $idScript = ['id' => 'scriptId', 'params' => ['param' => 'value']];
        $aggregation = new TermsAggregation('test_agg', 'test_field', $idScript);
        static::assertSame($idScript, $aggregation->getScript());
        static::assertSame('test_agg', $aggregation->getName());
        static::assertSame('test_field', $aggregation->getField());

        static::assertEquals([
            'terms' => [
                'field' => 'test_field',
                'script' => $idScript,
            ],
        ], $aggregation->toArray());
    }
}
