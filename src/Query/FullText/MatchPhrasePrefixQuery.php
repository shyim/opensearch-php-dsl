<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Query\FullText;

/**
 * Represents Elasticsearch "match_phrase_prefix" query.
 *
 * @author Ron Rademaker
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-match-query.html
 */
class MatchPhrasePrefixQuery extends MatchQuery
{
    public function getType(): string
    {
        return 'match_phrase_prefix';
    }
}
