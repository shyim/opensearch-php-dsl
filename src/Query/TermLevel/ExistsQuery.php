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

/**
 * Represents Elasticsearch "exists" query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-exists-query.html
 */
class ExistsQuery implements BuilderInterface
{
    private string $field;

    public function __construct(string $field)
    {
        $this->field = $field;
    }

    public function toArray(): array
    {
        return [
            $this->getType() => [
                'field' => $this->field,
            ],
        ];
    }

    public function getType(): string
    {
        return 'exists';
    }
}
