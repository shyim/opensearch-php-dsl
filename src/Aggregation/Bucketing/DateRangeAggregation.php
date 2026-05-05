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
 * Class representing date range aggregation.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-daterange-aggregation.html
 */
class DateRangeAggregation extends AbstractAggregation
{
    use BucketingTrait;

    private ?string $format;

    private array $ranges = [];

    private bool $keyed = false;

    public function __construct(
        string $name,
        string $field,
        ?string $format = null,
        array $ranges = [],
        bool $keyed = false,
    ) {
        parent::__construct($name);

        $this->setField($field);
        $this->setFormat($format);
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

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(?string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function addRange(?string $from = null, ?string $to = null, ?string $key = null): self
    {
        $range = array_filter(
            [
                'from' => $from,
                'to' => $to,
                'key' => $key,
            ],
            static fn ($v) => null !== $v
        );

        if (empty($range)) {
            throw new \LogicException('Either from or to must be set. Both cannot be null.');
        }

        $this->ranges[] = $range;

        return $this;
    }

    public function getArray()
    {
        if (empty($this->ranges)) {
            throw new \LogicException('Date range aggregation must have field and range added.');
        }

        $data = [
            'field' => $this->getField(),
            'ranges' => $this->ranges,
            'keyed' => $this->keyed,
        ];
        if ($this->getFormat()) {
            $data['format'] = $this->getFormat();
        }

        return $data;
    }

    public function getType(): string
    {
        return 'date_range';
    }
}
