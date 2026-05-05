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
use OpenSearchDSL\ScriptAwareTrait;

/**
 * Class representing Extended stats aggregation.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-extendedstats-aggregation.html
 */
class ExtendedStatsAggregation extends AbstractAggregation
{
    use MetricTrait;
    use ScriptAwareTrait;

    /**
     * @param string|array{id: string, params?: array<string, mixed>}|null $script
     */
    public function __construct(string $name, ?string $field = null, ?int $sigma = null, $script = null)
    {
        parent::__construct($name);

        $this->setField($field);
        $this->setSigma($sigma);
        $this->setScript($script);
    }

    private ?int $sigma;

    public function getSigma(): ?int
    {
        return $this->sigma;
    }

    public function setSigma(?int $sigma): self
    {
        $this->sigma = $sigma;

        return $this;
    }

    public function getArray(): array
    {
        return \array_filter(
            [
                'field' => $this->getField(),
                'script' => $this->getScript(),
                'sigma' => $this->getSigma(),
            ],
            static fn ($val) => $val || \is_numeric($val)
        );
    }

    public function getType(): string
    {
        return 'extended_stats';
    }
}
