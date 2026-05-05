<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\SearchEndpoint;

use OpenSearchDSL\BuilderInterface;
use OpenSearchDSL\Query\Compound\BoolQuery;

/**
 * Search query dsl endpoint.
 */
class QueryEndpoint extends AbstractSearchEndpoint
{
    /**
     * Endpoint name
     */
    public const NAME = 'query';
    private const DEFAULT_ORDER = 2;

    private ?BoolQuery $bool = null;

    private bool $filtersSet = false;

    public function normalize(): ?array
    {
        if (!$this->filtersSet && $this->hasReference('filter_query')) {
            /** @var BuilderInterface $filter */
            $filter = $this->getReference('filter_query');
            $this->addToBool($filter, BoolQuery::FILTER);
            $this->filtersSet = true;
        }

        if (!$this->bool) {
            return null;
        }

        return $this->bool->toArray();
    }

    public function add(BuilderInterface $builder, ?string $key = null): string
    {
        return $this->addToBool($builder, BoolQuery::MUST, $key);
    }

    public function addToBool(BuilderInterface $builder, ?string $boolType = null, $key = null): string
    {
        if (!$this->bool) {
            $this->bool = new BoolQuery();
        }

        return $this->bool->add($builder, $boolType, $key);
    }

    public function getOrder(): int
    {
        return self::DEFAULT_ORDER;
    }

    public function getBool(): ?BoolQuery
    {
        return $this->bool;
    }

    public function getAll(?string $boolType = null): array
    {
        return $this->bool->getQueries($boolType);
    }
}
