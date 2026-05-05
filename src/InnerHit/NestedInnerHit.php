<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\InnerHit;

/**
 * Represents Elasticsearch top level nested inner hits.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-request-inner-hits.html
 */
class NestedInnerHit extends AbstractInnerHit
{
    public const TYPE = 'nested';

    public function getType(): string
    {
        return self::TYPE;
    }
}
