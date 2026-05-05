<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Sort;

use OpenSearchDSL\BuilderInterface;
use OpenSearchDSL\ParametersTrait;

/**
 * Holds all the values required for basic sorting.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/sort-search-results.html
 */
class FieldSort implements BuilderInterface
{
    use ParametersTrait;

    public const ASC = 'asc';
    public const DESC = 'desc';

    private string $field;

    private ?string $order;

    private ?BuilderInterface $nestedFilter;

    public function __construct(
        string $field,
        ?string $order = null,
        ?BuilderInterface $nestedFilter = null,
        array $parameters = [],
    ) {
        $this->field = $field;
        $this->order = $order;
        $this->nestedFilter = $nestedFilter;
        $this->setParameters($parameters);
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function getOrder(): ?string
    {
        return $this->order;
    }

    public function setOrder(?string $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getNestedFilter(): ?BuilderInterface
    {
        return $this->nestedFilter;
    }

    public function setNestedFilter(?BuilderInterface $nestedFilter): self
    {
        $this->nestedFilter = $nestedFilter;

        return $this;
    }

    public function toArray(): array
    {
        if ($this->order) {
            $this->addParameter('order', $this->order);
        }

        if ($this->nestedFilter) {
            $this->addParameter('nested', $this->nestedFilter->toArray());
        }

        return [
            $this->field => $this->getParameters(),
        ];
    }

    public function getType(): string
    {
        return 'sort';
    }
}
