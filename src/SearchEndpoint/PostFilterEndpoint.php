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

/**
 * Search post filter dsl endpoint.
 */
class PostFilterEndpoint extends QueryEndpoint
{
    /**
     * Endpoint name
     */
    public const NAME = 'post_filter';
    private const DEFAULT_ORDER = 1;

    public function normalize(): ?array
    {
        if (!$this->getBool()) {
            return null;
        }

        return $this->getBool()->toArray();
    }

    public function getOrder(): int
    {
        return self::DEFAULT_ORDER;
    }
}
