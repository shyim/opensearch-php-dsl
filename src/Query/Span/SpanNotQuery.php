<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Query\Span;

use OpenSearchDSL\ParametersTrait;

/**
 * Elasticsearch Span not query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-span-not-query.html
 */
class SpanNotQuery implements SpanQueryInterface
{
    use ParametersTrait;

    private SpanQueryInterface $include;

    private SpanQueryInterface $exclude;

    public function __construct(SpanQueryInterface $include, SpanQueryInterface $exclude, array $parameters = [])
    {
        $this->include = $include;
        $this->exclude = $exclude;
        $this->setParameters($parameters);
    }

    public function toArray(): array
    {
        $query = [
            'include' => $this->include->toArray(),
            'exclude' => $this->exclude->toArray(),
        ];

        return [$this->getType() => $this->processArray($query)];
    }

    public function getType(): string
    {
        return 'span_not';
    }
}
