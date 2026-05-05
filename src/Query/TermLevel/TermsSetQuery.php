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
 * Represents Elasticsearch "terms_set" query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-terms-set-query.html
 */
class TermsSetQuery implements BuilderInterface
{
    use ParametersTrait;

    public const MINIMUM_SHOULD_MATCH_TYPE_FIELD = 'minimum_should_match_field';
    public const MINIMUM_SHOULD_MATCH_TYPE_SCRIPT = 'minimum_should_match_script';

    private string $field;

    private array $terms;

    public function __construct(string $field, array $terms, array $parameters)
    {
        $this->field = $field;
        $this->terms = $terms;
        $this->validateParameters($parameters);
        $this->setParameters($parameters);
    }

    public function toArray(): array
    {
        $query = [
            'terms' => $this->terms,
        ];

        return [$this->getType() => [
            $this->field => $this->processArray($query),
        ]];
    }

    public function getType(): string
    {
        return 'terms_set';
    }

    private function validateParameters(array $parameters): void
    {
        if (!isset($parameters[self::MINIMUM_SHOULD_MATCH_TYPE_FIELD])
            && !isset($parameters[self::MINIMUM_SHOULD_MATCH_TYPE_SCRIPT])
        ) {
            $message = 'Either minimum_should_match_field or minimum_should_match_script must be set.';
            throw new \InvalidArgumentException($message);
        }
    }
}
