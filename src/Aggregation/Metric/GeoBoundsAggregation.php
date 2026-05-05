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
 * Class representing geo bounds aggregation.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-geobounds-aggregation.html
 */
class GeoBoundsAggregation extends AbstractAggregation
{
    use MetricTrait;

    private bool $wrapLongitude = true;

    public function __construct(string $name, string $field, bool $wrapLongitude = true)
    {
        parent::__construct($name);

        $this->setField($field);
        $this->setWrapLongitude($wrapLongitude);
    }

    public function isWrapLongitude(): bool
    {
        return $this->wrapLongitude;
    }

    public function setWrapLongitude(bool $wrapLongitude): self
    {
        $this->wrapLongitude = $wrapLongitude;

        return $this;
    }

    public function getArray(): array
    {
        $data = [];
        $data['field'] = $this->getField();

        $data['wrap_longitude'] = $this->isWrapLongitude();

        return $data;
    }

    public function getType(): string
    {
        return 'geo_bounds';
    }
}
