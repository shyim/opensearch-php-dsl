<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Aggregation;

use OpenSearchDSL\BuilderBag;
use OpenSearchDSL\BuilderInterface;
use OpenSearchDSL\NameAwareTrait;
use OpenSearchDSL\NamedBuilderInterface;
use OpenSearchDSL\ParametersTrait;

/**
 * AbstractAggregation class.
 */
abstract class AbstractAggregation implements NamedBuilderInterface
{
    use NameAwareTrait;
    use ParametersTrait;

    private ?string $field = null;

    private ?BuilderBag $aggregations = null;

    abstract protected function supportsNesting(): bool;

    /**
     * @return array|\stdClass
     */
    abstract protected function getArray();

    public function __construct(string $name)
    {
        $this->setName($name);
    }

    public function setField(?string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function getField(): ?string
    {
        return $this->field;
    }

    public function addAggregation(self $abstractAggregation): self
    {
        if (!$this->aggregations) {
            $this->aggregations = $this->createBuilderBag();
        }

        $this->aggregations->add($abstractAggregation);

        return $this;
    }

    /**
     * @return BuilderInterface[]
     */
    public function getAggregations()
    {
        if ($this->aggregations) {
            return $this->aggregations->all();
        }

        return [];
    }

    public function getAggregation(string $name): ?BuilderInterface
    {
        if ($this->aggregations && $this->aggregations->has($name)) {
            return $this->aggregations->get($name);
        }

        return null;
    }

    public function toArray(): array
    {
        $array = $this->getArray();
        $result = [
            $this->getType() => \is_array($array) ? $this->processArray($array) : $array,
        ];

        if ($this->supportsNesting()) {
            $nestedResult = $this->collectNestedAggregations();

            if (!empty($nestedResult)) {
                $result['aggregations'] = $nestedResult;
            }
        }

        return $result;
    }

    private function collectNestedAggregations(): array
    {
        $result = [];

        /** @var AbstractAggregation $aggregation */
        foreach ($this->getAggregations() as $aggregation) {
            $result[$aggregation->getName()] = $aggregation->toArray();
        }

        return $result;
    }

    private function createBuilderBag(): BuilderBag
    {
        return new BuilderBag();
    }
}
