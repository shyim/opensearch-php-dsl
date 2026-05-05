<?php declare(strict_types=1);

namespace OpenSearchDSL\Tests\Unit\Aggregation;

use OpenSearchDSL\Aggregation\AbstractAggregation;
use OpenSearchDSL\Aggregation\Bucketing\FilterAggregation;
use OpenSearchDSL\Aggregation\Bucketing\TermsAggregation;
use OpenSearchDSL\NamedBuilderInterface;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class AbstractAggregationTest extends TestCase
{
    public function testGetAggregation(): void
    {
        $subAgg = new NamedTestAggregation('foo_bar');

        $agg = $this->getMockBuilder(AbstractAggregation::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass()
        ;

        $agg->addAggregation($subAgg);

        static::assertSame($subAgg, $agg->getAggregation('foo_bar'));

        $aggs = $agg->getAggregations();
        static::assertArrayHasKey('foo_bar', $aggs);
        static::assertSame($subAgg, $aggs['foo_bar']);
    }

    public function testGetUnavailableAggregation(): void
    {
        $subAgg = new NamedTestAggregation('foo_bar');

        $agg = $this->getMockBuilder(AbstractAggregation::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass()
        ;

        $agg->addAggregation($subAgg);

        static::assertNull($agg->getAggregation('invalid'));
    }

    public function testGetField(): void
    {
        $agg = $this->getMockBuilder(AbstractAggregation::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass()
        ;

        static::assertNull($agg->getField());
    }

    public function testMultipleAggregations(): void
    {
        $agg = new TermsAggregation('foo');

        $agg->addAggregation(new FilterAggregation('foo_bar', new TermsAggregation('foo_bar')));
        $agg->addAggregation(new TermsAggregation('foo_baz'));

        $aggs = $agg->getAggregations();
        static::assertArrayHasKey('foo_bar', $aggs);
        static::assertArrayHasKey('foo_baz', $aggs);

        static::assertSame(
            [
                'terms' => [],
                'aggregations' => [
                    'foo_bar' => [
                        'filter' => [
                            'terms' => [],
                        ],
                    ],
                    'foo_baz' => [
                        'terms' => [],
                    ],
                ],
            ],
            $agg->toArray()
        );
    }
}

class NamedTestAggregation extends AbstractAggregation implements NamedBuilderInterface
{
    public function toArray(): array
    {
        return [];
    }

    public function getType(): string
    {
        return 'bar';
    }

    protected function getArray(): array
    {
        return [];
    }

    protected function supportsNesting(): bool
    {
        return false;
    }
}
