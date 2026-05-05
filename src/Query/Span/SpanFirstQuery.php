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
 * Elasticsearch span first query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-span-first-query.html
 */
class SpanFirstQuery implements SpanQueryInterface
{
    use ParametersTrait;

    private SpanQueryInterface $query;

    private int $end;

    public function __construct(SpanQueryInterface $query, int $end, array $parameters = [])
    {
        $this->query = $query;
        $this->end = $end;
        $this->setParameters($parameters);
    }

    public function toArray(): array
    {
        $query = [];
        $query['match'] = $this->query->toArray();
        $query['end'] = $this->end;
        $output = $this->processArray($query);

        return [$this->getType() => $output];
    }

    public function getType(): string
    {
        return 'span_first';
    }
}
