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

use OpenSearchDSL\Suggest\Suggest;

/**
 * Search suggest dsl endpoint.
 */
class SuggestEndpoint extends AbstractSearchEndpoint
{
    /**
     * Endpoint name
     */
    public const NAME = 'suggest';

    public function normalize(): ?array
    {
        $output = [];
        /** @var Suggest $suggest */
        foreach ($this->getAll() as $suggest) {
            $output = array_merge($output, $suggest->toArray());
        }

        return $output;
    }
}
