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
 * Class representing composite aggregation.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-composite-aggregation.html
 */
class CompositeAggregation extends AbstractAggregation
{
    use BucketingTrait;

    private array $sources = [];

    private ?int $size = null;

    private array $after = [];

    /**
     * @param AbstractAggregation[] $sources
     */
    public function __construct(string $name, array $sources = [])
    {
        parent::__construct($name);

        foreach ($sources as $agg) {
            $this->addSource($agg);
        }
    }

    public function addSource(AbstractAggregation $agg): self
    {
        $array = $agg->processArray($agg->getArray());

        $this->sources[] = [
            $agg->getName() => [$agg->getType() => $array],
        ];

        return $this;
    }

    public function getSources(): array
    {
        return $this->sources;
    }

    public function setSources(array $sources): self
    {
        $this->sources = $sources;

        return $this;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setAfter(array $after): self
    {
        $this->after = $after;

        return $this;
    }

    public function getAfter(): array
    {
        return $this->after;
    }

    public function getArray(): array
    {
        $array = [
            'sources' => $this->sources,
        ];

        if ($this->size !== null) {
            $array['size'] = $this->size;
        }

        if (!empty($this->after)) {
            $array['after'] = $this->after;
        }

        return $array;
    }

    public function getType(): string
    {
        return 'composite';
    }
}
