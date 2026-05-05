<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Query\Span;

use OpenSearchDSL\Query\TermLevel\TermQuery;

/**
 * Elasticsearch span_term query class.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-span-term-query.html
 */
class SpanTermQuery extends TermQuery implements SpanQueryInterface
{
    public function getType(): string
    {
        return 'span_term';
    }
}
