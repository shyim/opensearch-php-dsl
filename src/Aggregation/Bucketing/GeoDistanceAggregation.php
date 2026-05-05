<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Aggregation\Bucketing;

use OpenSearchDSL\Aggregation\AbstractAggregation;
use OpenSearchDSL\Aggregation\Type\BucketingTrait;

/**
 * Class representing geo distance aggregation.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-geodistance-aggregation.html
 */
class GeoDistanceAggregation extends AbstractAggregation
{
    use BucketingTrait;

    private string $origin;

    private ?string $distanceType;

    private ?string $unit;

    private array $ranges = [];

    public function __construct(
        string $name,
        string $field,
        string $origin,
        array $ranges = [],
        ?string $unit = null,
        ?string $distanceType = null,
    ) {
        parent::__construct($name);

        $this->setField($field);
        $this->setOrigin($origin);

        foreach ($ranges as $range) {
            $from = $range['from'] ?? null;
            $to = $range['to'] ?? null;
            $this->addRange($from, $to);
        }

        $this->setUnit($unit);
        $this->setDistanceType($distanceType);
    }

    public function getOrigin(): string
    {
        return $this->origin;
    }

    public function setOrigin(string $origin): self
    {
        $this->origin = $origin;

        return $this;
    }

    public function getDistanceType(): ?string
    {
        return $this->distanceType;
    }

    public function setDistanceType(?string $distanceType): self
    {
        $this->distanceType = $distanceType;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function addRange(?float $from = null, ?float $to = null): self
    {
        $range = array_filter(
            [
                'from' => $from,
                'to' => $to,
            ],
            static fn ($v) => null !== $v
        );

        if (empty($range)) {
            throw new \LogicException('Either from or to must be set. Both cannot be null.');
        }

        $this->ranges[] = $range;

        return $this;
    }

    public function getArray(): array
    {
        $data = [
            'field' => $this->getField(),
            'origin' => $this->getOrigin(),
        ];

        if ($this->getUnit()) {
            $data['unit'] = $this->getUnit();
        }

        if ($this->getDistanceType()) {
            $data['distance_type'] = $this->getDistanceType();
        }

        $data['ranges'] = $this->ranges;

        return $data;
    }

    public function getType(): string
    {
        return 'geo_distance';
    }
}
