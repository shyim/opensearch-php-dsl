<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Query\Geo;

use OpenSearchDSL\BuilderInterface;
use OpenSearchDSL\ParametersTrait;

/**
 * Represents Elasticsearch "geo_polygon" query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-polygon-query.html
 */
class GeoPolygonQuery implements BuilderInterface
{
    use ParametersTrait;

    private string $field;

    private array $points;

    public function __construct(string $field, array $points = [], array $parameters = [])
    {
        $this->field = $field;
        $this->points = $points;
        $this->setParameters($parameters);
    }

    public function getType(): string
    {
        return 'geo_polygon';
    }

    public function toArray(): array
    {
        $query = [$this->field => ['points' => $this->points]];
        $output = $this->processArray($query);

        return [$this->getType() => $output];
    }
}
