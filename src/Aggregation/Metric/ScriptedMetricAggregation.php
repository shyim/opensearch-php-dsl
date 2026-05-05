<?php declare(strict_types=1);

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

/**
 * Class representing ScriptedMetricAggregation.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-scripted-metric-aggregation.html
 */
class ScriptedMetricAggregation extends AbstractAggregation
{
    use MetricTrait;

    private ?string $initScript;

    private ?string $mapScript;

    private ?string $combineScript;

    private ?string $reduceScript;

    public function __construct(
        string $name,
        ?string $initScript = null,
        ?string $mapScript = null,
        ?string $combineScript = null,
        ?string $reduceScript = null,
    ) {
        parent::__construct($name);

        $this->setInitScript($initScript);
        $this->setMapScript($mapScript);
        $this->setCombineScript($combineScript);
        $this->setReduceScript($reduceScript);
    }

    public function getInitScript(): ?string
    {
        return $this->initScript;
    }

    public function setInitScript(?string $initScript): self
    {
        $this->initScript = $initScript;

        return $this;
    }

    public function getMapScript(): ?string
    {
        return $this->mapScript;
    }

    public function setMapScript(?string $mapScript): self
    {
        $this->mapScript = $mapScript;

        return $this;
    }

    public function getCombineScript(): ?string
    {
        return $this->combineScript;
    }

    public function setCombineScript(?string $combineScript): self
    {
        $this->combineScript = $combineScript;

        return $this;
    }

    public function getReduceScript(): ?string
    {
        return $this->reduceScript;
    }

    public function setReduceScript(?string $reduceScript): self
    {
        $this->reduceScript = $reduceScript;

        return $this;
    }

    public function getArray(): array
    {
        return array_filter(
            [
                'init_script' => $this->getInitScript(),
                'map_script' => $this->getMapScript(),
                'combine_script' => $this->getCombineScript(),
                'reduce_script' => $this->getReduceScript(),
            ]
        );
    }

    public function getType(): string
    {
        return 'scripted_metric';
    }
}
