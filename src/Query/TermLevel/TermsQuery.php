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
 * Represents Elasticsearch "terms" query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-terms-query.html
 */
class TermsQuery implements BuilderInterface
{
    use ParametersTrait;

    private string $field;

    private array $terms;

    public function __construct(string $field, array $terms, array $parameters = [])
    {
        $this->field = $field;
        $this->terms = $terms;
        $this->setParameters($parameters);
    }

    public function toArray(): array
    {
        $query = [
            $this->field => $this->terms,
        ];

        $output = $this->processArray($query);

        return [$this->getType() => $output];
    }

    public function getType(): string
    {
        return 'terms';
    }
}
