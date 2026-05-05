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
 * Class representing StatsAggregation.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-stats-aggregation.html
 */
class StatsAggregation extends AbstractAggregation
{
    use MetricTrait;
    use ScriptAwareTrait;

    /**
     * @param string|array{id: string, params?: array<string, mixed>}|null $script
     */
    public function __construct(string $name, ?string $field = null, $script = null)
    {
        parent::__construct($name);

        $this->setField($field);
        $this->setScript($script);
    }

    public function getArray(): array
    {
        $out = [];

        if ($this->getField()) {
            $out['field'] = $this->getField();
        }

        if ($this->getScript()) {
            $out['script'] = $this->getScript();
        }

        return $out;
    }

    public function getType(): string
    {
        return 'stats';
    }
}
