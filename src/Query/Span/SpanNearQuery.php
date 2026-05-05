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

/**
 * Elasticsearch span near query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-span-near-query.html
 */
class SpanNearQuery extends SpanOrQuery implements SpanQueryInterface
{
    private ?int $slop;

    public function __construct(?int $slop = null, array $queries = [], array $parameters = [])
    {
        $this->slop = $slop;
        parent::__construct($queries, $parameters);
    }

    public function getSlop(): ?int
    {
        return $this->slop;
    }

    public function setSlop(?int $slop): self
    {
        $this->slop = $slop;

        return $this;
    }

    public function toArray(): array
    {
        $query = [];

        foreach ($this->getQueries() as $type) {
            $query['clauses'][] = $type->toArray();
        }

        if ($this->getSlop()) {
            $query['slop'] = $this->getSlop();
        }

        $output = $this->processArray($query);

        return [$this->getType() => $output];
    }

    public function getType(): string
    {
        return 'span_near';
    }
}
