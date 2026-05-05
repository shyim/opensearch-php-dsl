<?php declare(strict_types=1);

namespace OpenSearchDSL\Type;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-distance-query.html
 */
class Location implements TypeInterface
{
    private float $lat;

    private float $lon;

    public function __construct(float $lat, float $lon)
    {
        $this->lat = $lat;
        $this->lon = $lon;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLon(): float
    {
        return $this->lon;
    }

    public function setLon(float $lon): self
    {
        $this->lon = $lon;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'lat' => $this->getLat(),
            'lon' => $this->getLon(),
        ];
    }
}
