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
 * Represents Elasticsearch "regexp" query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-regexp-query.html
 */
class RegexpQuery implements BuilderInterface
{
    use ParametersTrait;

    private string $field;

    private string $regexpValue;

    public function __construct($field, $regexpValue, array $parameters = [])
    {
        $this->field = $field;
        $this->regexpValue = $regexpValue;
        $this->setParameters($parameters);
    }

    public function toArray(): array
    {
        $query = [
            'value' => $this->regexpValue,
        ];

        $output = [
            $this->field => $this->processArray($query),
        ];

        return [$this->getType() => $output];
    }

    public function getType(): string
    {
        return 'regexp';
    }
}
