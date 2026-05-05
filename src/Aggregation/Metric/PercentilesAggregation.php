<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Aggregation\Metric;

use OpenSearchDSL\Aggregation\AbstractAggregation;
use OpenSearchDSL\Aggregation\Type\MetricTrait;
use OpenSearchDSL\ScriptAwareTrait;

/**
 * Class representing PercentilesAggregation.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-percentile-aggregation.html
 */
class PercentilesAggregation extends AbstractAggregation
{
    use MetricTrait;
    use ScriptAwareTrait;

    private ?array $percents;

    /**
     * @param string|array{id: string, params?: array<string, mixed>}|null $script
     */
    public function __construct(string $name, ?string $field = null, ?array $percents = null, $script = null)
    {
        parent::__construct($name);

        $this->setField($field);
        $this->setPercents($percents);
        $this->setScript($script);
    }

    public function getPercents(): ?array
    {
        return $this->percents;
    }

    public function setPercents(?array $percents): self
    {
        $this->percents = $percents;

        return $this;
    }

    public function getArray(): array
    {
        $out = \array_filter(
            [
                'percents' => $this->getPercents(),
                'field' => $this->getField(),
                'script' => $this->getScript(),
            ],
            static fn ($val) => $val || \is_numeric($val)
        );

        $this->isRequiredParametersSet($out);

        return $out;
    }

    public function getType(): string
    {
        return 'percentiles';
    }

    private function isRequiredParametersSet(array $out): void
    {
        if (\array_key_exists('field', $out) || \array_key_exists('script', $out)) {
            return;
        }

        throw new \LogicException('Percentiles aggregation must have field or script set.');
    }
}
