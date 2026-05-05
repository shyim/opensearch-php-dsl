<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Aggregation\Matrix;

use OpenSearchDSL\Aggregation\AbstractAggregation;
use OpenSearchDSL\Aggregation\Type\MetricTrait;

/**
 * Class representing Max Aggregation.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-max-aggregation.html
 */
class MaxAggregation extends AbstractAggregation
{
    use MetricTrait;

    private array $fields;

    private ?string $mode = null;

    private ?array $missing = null;

    /**
     * @param array|string $field
     */
    public function __construct(string $name, $field, ?array $missing = null, ?string $mode = null)
    {
        parent::__construct($name);

        $this->setFields(is_string($field) ? [$field] : $field);
        $this->setMode($mode);
        $this->setMissing($missing);
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function setFields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getMissing(): ?array
    {
        return $this->missing;
    }

    public function setMissing(?array $missing): self
    {
        $this->missing = $missing;

        return $this;
    }

    protected function getArray(): array
    {
        $out = [
            'fields' => $this->getField(),
        ];

        if ($this->getMode()) {
            $out['mode'] = $this->getMode();
        }

        if ($this->getMissing()) {
            $out['missing'] = $this->getMissing();
        }

        return $out;
    }

    public function getType(): string
    {
        return 'matrix_stats';
    }
}
