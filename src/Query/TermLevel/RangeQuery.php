<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Query\TermLevel;

use OpenSearchDSL\BuilderInterface;
use OpenSearchDSL\ParametersTrait;

/**
 * Represents Elasticsearch "range" query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-range-query.html
 */
class RangeQuery implements BuilderInterface
{
    use ParametersTrait;

    /**
     * Range control names.
     */
    public const LT = 'lt';
    public const GT = 'gt';
    public const LTE = 'lte';
    public const GTE = 'gte';

    private string $field;

    public function __construct(string $field, array $parameters = [])
    {
        $this->setParameters($parameters);

        if ($this->hasParameter(self::GTE) && $this->hasParameter(self::GT)) {
            throw new \LogicException('Range query cannot have "gte" and "gt" parameters');
        }

        if ($this->hasParameter(self::LTE) && $this->hasParameter(self::LT)) {
            throw new \LogicException('Range query cannot have "lte" and "lt" parameters');
        }

        $this->field = $field;
    }

    public function toArray(): array
    {
        $output = [
            $this->field => $this->getParameters(),
        ];

        return [$this->getType() => $output];
    }

    public function getType(): string
    {
        return 'range';
    }
}
