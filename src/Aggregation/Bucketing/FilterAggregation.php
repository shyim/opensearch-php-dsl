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
use OpenSearchDSL\BuilderInterface;

/**
 * Class representing FilterAggregation.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-filter-aggregation.html
 */
class FilterAggregation extends AbstractAggregation
{
    use BucketingTrait;

    protected BuilderInterface $filter;

    public function __construct(string $name, BuilderInterface $filter)
    {
        parent::__construct($name);

        $this->setFilter($filter);
    }

    public function setFilter(BuilderInterface $filter): self
    {
        $this->filter = $filter;

        return $this;
    }

    public function getFilter(): BuilderInterface
    {
        return $this->filter;
    }

    public function setField(?string $field): self
    {
        throw new \LogicException("Filter aggregation, doesn't support `field` parameter");
    }

    public function getArray(): array
    {
        return $this->getFilter()->toArray();
    }

    public function getType(): string
    {
        return 'filter';
    }
}
