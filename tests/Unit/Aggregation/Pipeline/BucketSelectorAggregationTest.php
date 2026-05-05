<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\Aggregation\Pipeline;

use OpenSearchDSL\Aggregation\Pipeline\BucketSelectorAggregation;

/**
 * Unit test for bucket selector pipeline aggregation.
 *
 * @internal
 */
class BucketSelectorAggregationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests toArray method.
     */
    public function testToArray(): void
    {
        $aggregation = new BucketSelectorAggregation(
            'test',
            [
                'my_var1' => 'foo',
                'my_var2' => 'bar',
            ]
        );
        $aggregation->setScript('foo > bar');

        $expected = [
            'bucket_selector' => [
                'buckets_path' => [
                    'my_var1' => 'foo',
                    'my_var2' => 'bar',
                ],
                'script' => 'foo > bar',
            ],
        ];

        static::assertEquals($expected, $aggregation->toArray());
    }

    /**
     * Tests if the exception is thrown in getArray method if no
     * buckets_path or script is set
     */
    public function testGetArrayException(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('`test` aggregation must have script set.');
        $agg = new BucketSelectorAggregation('test', []);

        $agg->getArray();
    }
}
