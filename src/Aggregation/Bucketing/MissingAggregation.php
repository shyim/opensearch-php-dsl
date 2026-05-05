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
 * Class representing missing aggregation.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-missing-aggregation.html
 */
class MissingAggregation extends AbstractAggregation
{
    use BucketingTrait;

    public function __construct(string $name, string $field)
    {
        parent::__construct($name);

        $this->setField($field);
    }

    public function getArray()
    {
        return ['field' => $this->getField()];
    }

    public function getType(): string
    {
        return 'missing';
    }
}
