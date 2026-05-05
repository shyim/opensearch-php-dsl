<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Query\Compound;

use OpenSearchDSL\BuilderInterface;
use OpenSearchDSL\ParametersTrait;

/**
 * Represents Elasticsearch "dis_max" query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-dis-max-query.html
 */
class DisMaxQuery implements BuilderInterface
{
    use ParametersTrait;

    /**
     * @var BuilderInterface[]
     */
    private array $queries = [];

    public function __construct(array $parameters = [])
    {
        $this->setParameters($parameters);
    }

    public function addQuery(BuilderInterface $query): self
    {
        $this->queries[] = $query;

        return $this;
    }

    public function toArray(): array
    {
        $query = [];
        foreach ($this->queries as $type) {
            $query[] = $type->toArray();
        }
        $output = $this->processArray(['queries' => $query]);

        return [$this->getType() => $output];
    }

    public function getType(): string
    {
        return 'dis_max';
    }
}
