<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Query\Joining;

use OpenSearchDSL\BuilderInterface;
use OpenSearchDSL\ParametersTrait;

/**
 * Represents Elasticsearch "nested" query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-nested-query.html
 */
class NestedQuery implements BuilderInterface
{
    use ParametersTrait;

    private string $path;

    private BuilderInterface $query;

    public function __construct(string $path, BuilderInterface $query, array $parameters = [])
    {
        $this->path = $path;
        $this->query = $query;
        $this->parameters = $parameters;
    }

    public function getQuery(): BuilderInterface
    {
        return $this->query;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function toArray(): array
    {
        return [
            $this->getType() => $this->processArray(
                [
                    'path' => $this->getPath(),
                    'query' => $this->getQuery()->toArray(),
                ]
            ),
        ];
    }

    public function getType(): string
    {
        return 'nested';
    }
}
