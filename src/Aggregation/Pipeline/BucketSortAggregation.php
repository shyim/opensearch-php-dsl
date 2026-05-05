<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Aggregation\Pipeline;

use OpenSearchDSL\Sort\FieldSort;

/**
 * Class representing Bucket Script Pipeline Aggregation.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-pipeline-bucket-sort-aggregation.html
 */
class BucketSortAggregation extends AbstractPipelineAggregation
{
    private array $sort = [];

    public function __construct(string $name, ?string $bucketsPath = null)
    {
        parent::__construct($name, $bucketsPath);
    }

    public function getSort(): array
    {
        return $this->sort;
    }

    public function setSort(array $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function addSort(FieldSort $sort): self
    {
        $this->sort[] = $sort->toArray();

        return $this;
    }

    public function getArray()
    {
        return \array_filter(
            [
                'buckets_path' => $this->getBucketsPath(),
                'sort' => $this->getSort(),
            ]
        );
    }

    public function getType(): string
    {
        return 'bucket_sort';
    }
}
