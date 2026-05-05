<?php declare(strict_types=1);

namespace OpenSearchDSL\Tests\Unit\InnerHit;

use OpenSearchDSL\InnerHit\ParentInnerHit;
use OpenSearchDSL\Query\TermLevel\TermQuery;
use OpenSearchDSL\Search;

/**
 * @internal
 */
class ParentInnerHitTest extends \PHPUnit\Framework\TestCase
{
    public function testToArray(): void
    {
        $query = new TermQuery('foo', 'bar');
        $search = new Search();
        $search->addQuery($query);

        $hit = new ParentInnerHit('test', 'acme', $search);
        $hit->setParameters(['size' => 10]);
        $expected = [
            'type' => [
                'acme' => [
                    'query' => $query->toArray(),
                ],
            ],
        ];
        static::assertEquals($expected, $hit->toArray());
    }
}
