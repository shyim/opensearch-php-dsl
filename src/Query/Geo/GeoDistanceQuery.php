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
use OpenSearchDSL\Type\Location;

/**
 * Represents Elasticsearch "geo_distance" query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-distance-query.html
 */
class GeoDistanceQuery implements BuilderInterface
{
    use ParametersTrait;

    private string $field;

    private string $distance;

    private Location $location;

    public function __construct(string $field, string $distance, Location $location, array $parameters = [])
    {
        $this->field = $field;
        $this->distance = $distance;
        $this->location = $location;

        $this->setParameters($parameters);
    }

    public function toArray(): array
    {
        $query = [
            'distance' => $this->distance,
            $this->field => $this->location->toArray(),
        ];
        $output = $this->processArray($query);

        return [$this->getType() => $output];
    }

    public function getType(): string
    {
        return 'geo_distance';
    }
}
