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

use OpenSearchDSL\InnerHit\NestedInnerHit;

/**
 * Search inner hits dsl endpoint.
 */
class InnerHitsEndpoint extends AbstractSearchEndpoint
{
    /**
     * Endpoint name
     */
    public const NAME = 'inner_hits';

    public function normalize(): ?array
    {
        $output = [];
        /** @var NestedInnerHit $innerHit */
        foreach ($this->getAll() as $innerHit) {
            $output[$innerHit->getName()] = $innerHit->toArray();
        }

        return $output;
    }
}
