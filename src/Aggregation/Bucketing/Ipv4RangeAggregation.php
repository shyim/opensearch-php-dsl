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
 * Class representing ip range aggregation.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-iprange-aggregation.html
 */
class Ipv4RangeAggregation extends AbstractAggregation
{
    use BucketingTrait;

    private array $ranges = [];

    public function __construct($name, string $field, array $ranges = [])
    {
        parent::__construct($name);

        $this->setField($field);

        foreach ($ranges as $range) {
            if (\is_array($range)) {
                $from = $range['from'] ?? null;
                $to = $range['to'] ?? null;

                $this->addRange($from, $to);
                continue;
            }

            $this->addMask($range);
        }
    }

    public function addRange(?string $from = null, ?string $to = null): self
    {
        $range = \array_filter(
            [
                'from' => $from,
                'to' => $to,
            ],
            static fn ($v) => null !== $v
        );

        $this->ranges[] = $range;

        return $this;
    }

    public function addMask(string $mask): self
    {
        $this->ranges[] = ['mask' => $mask];

        return $this;
    }

    public function getArray(): array
    {
        if (empty($this->ranges)) {
            throw new \LogicException('Ip range aggregation must have field set and range added.');
        }

        return [
            'field' => $this->getField(),
            'ranges' => $this->ranges,
        ];
    }

    public function getType(): string
    {
        return 'ip_range';
    }
}
