<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Aggregation\Bucketing;

use OpenSearchDSL\Aggregation\AbstractAggregation;
use OpenSearchDSL\Aggregation\Type\BucketingTrait;

/**
 * Class representing geo bounds aggregation.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/2.3/search-aggregations-bucket-sampler-aggregation.html
 */
class SamplerAggregation extends AbstractAggregation
{
    use BucketingTrait;

    private ?int $shardSize;

    public function __construct(string $name, ?string $field = null, ?int $shardSize = null)
    {
        parent::__construct($name);

        $this->setField($field);
        $this->setShardSize($shardSize);
    }

    public function getShardSize(): ?int
    {
        return $this->shardSize;
    }

    public function setShardSize(?int $shardSize): self
    {
        $this->shardSize = $shardSize;

        return $this;
    }

    public function getArray(): array
    {
        return \array_filter(
            [
                'field' => $this->getField(),
                'shard_size' => $this->getShardSize(),
            ]
        );
    }

    public function getType(): string
    {
        return 'sampler';
    }
}
