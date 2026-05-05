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

/**
 * Represents Elasticsearch "boosting" query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-boosting-query.html
 */
class BoostingQuery implements BuilderInterface
{
    private BuilderInterface $positive;

    private BuilderInterface $negative;

    private float $negativeBoost;

    public function __construct(BuilderInterface $positive, BuilderInterface $negative, float $negativeBoost)
    {
        $this->positive = $positive;
        $this->negative = $negative;
        $this->negativeBoost = $negativeBoost;
    }

    public function toArray(): array
    {
        $query = [
            'positive' => $this->positive->toArray(),
            'negative' => $this->negative->toArray(),
            'negative_boost' => $this->negativeBoost,
        ];

        return [$this->getType() => $query];
    }

    public function getType(): string
    {
        return 'boosting';
    }
}
