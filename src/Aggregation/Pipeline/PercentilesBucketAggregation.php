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

/**
 * Class representing Percentiles Bucket Pipeline Aggregation.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-pipeline-percentiles-bucket-aggregation.html
 */
class PercentilesBucketAggregation extends AbstractPipelineAggregation
{
    private array $percents = [];

    public function getPercents(): array
    {
        return $this->percents;
    }

    public function setPercents(array $percents): self
    {
        $this->percents = $percents;

        return $this;
    }

    public function getArray(): array
    {
        $data = ['buckets_path' => $this->getBucketsPath()];

        if ($this->getPercents()) {
            $data['percents'] = $this->getPercents();
        }

        return $data;
    }

    public function getType(): string
    {
        return 'percentiles_bucket';
    }
}
