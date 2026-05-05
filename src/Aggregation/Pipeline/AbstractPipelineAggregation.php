<?php declare(strict_types=1);

namespace OpenSearchDSL\Aggregation\Pipeline;

use OpenSearchDSL\Aggregation\AbstractAggregation;
use OpenSearchDSL\Aggregation\Type\MetricTrait;

abstract class AbstractPipelineAggregation extends AbstractAggregation
{
    use MetricTrait;

    /**
     * @var array|string
     */
    private $bucketsPath;

    public function __construct(string $name, $bucketsPath)
    {
        parent::__construct($name);

        $this->setBucketsPath($bucketsPath);
    }

    /**
     * @return array|string
     */
    public function getBucketsPath()
    {
        return $this->bucketsPath;
    }

    /**
     * @param array|string $bucketsPath
     */
    public function setBucketsPath($bucketsPath): self
    {
        $this->bucketsPath = $bucketsPath;

        return $this;
    }

    public function getArray()
    {
        return ['buckets_path' => $this->getBucketsPath()];
    }
}
