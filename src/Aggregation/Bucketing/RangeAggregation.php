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
 * Class representing RangeAggregation.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-range-aggregation.html
 */
class RangeAggregation extends AbstractAggregation
{
    use BucketingTrait;

    private array $ranges = [];

    private bool $keyed = false;

    public function __construct(string $name, ?string $field = null, array $ranges = [], bool $keyed = false)
    {
        parent::__construct($name);

        $this->setField($field);
        $this->setKeyed($keyed);

        foreach ($ranges as $range) {
            $from = $range['from'] ?? null;
            $to = $range['to'] ?? null;
            $key = $range['key'] ?? null;

            $this->addRange($from, $to, $key);
        }
    }

    public function setKeyed(bool $keyed): self
    {
        $this->keyed = $keyed;

        return $this;
    }

    public function addRange(?float $from = null, ?float $to = null, ?string $key = ''): self
    {
        $range = array_filter(
            [
                'from' => $from,
                'to' => $to,
            ],
            static fn ($v) => null !== $v
        );

        if ($key) {
            $range['key'] = $key;
        }

        $this->ranges[] = $range;

        return $this;
    }

    public function removeRange(?float $from, ?float $to): bool
    {
        foreach ($this->ranges as $key => $range) {
            if (\array_diff_assoc(\array_filter(['from' => $from, 'to' => $to]), $range) === []) {
                unset($this->ranges[$key]);

                return true;
            }
        }

        return false;
    }

    public function removeRangeByKey(string $key): bool
    {
        if ($this->keyed) {
            foreach ($this->ranges as $rangeKey => $range) {
                if (\array_key_exists('key', $range) && $range['key'] === $key) {
                    unset($this->ranges[$rangeKey]);

                    return true;
                }
            }
        }

        return false;
    }

    public function getArray(): array
    {
        $data = [
            'keyed' => $this->keyed,
            'ranges' => \array_values($this->ranges),
        ];

        if ($this->getField()) {
            $data['field'] = $this->getField();
        }

        return $data;
    }

    public function getType(): string
    {
        return 'range';
    }
}
