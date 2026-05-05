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
 * Represents Elasticsearch "term" query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-term-query.html
 */
class TermQuery implements BuilderInterface
{
    use ParametersTrait;

    private string $field;

    private $value;

    /**
     * @param string|int|float|bool $value
     */
    public function __construct(string $field, $value, array $parameters = [])
    {
        $this->field = $field;
        $this->value = $value;
        $this->setParameters($parameters);
    }

    public function toArray(): array
    {
        $query = $this->processArray();

        if (empty($query)) {
            $query = $this->value;
        } else {
            $query['value'] = $this->value;
        }

        $output = [
            $this->field => $query,
        ];

        return [$this->getType() => $output];
    }

    public function getType(): string
    {
        return 'term';
    }
}
