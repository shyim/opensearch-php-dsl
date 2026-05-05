<?php declare(strict_types=1);

namespace OpenSearchDSL\Tests\Unit\Query\Joining;

use OpenSearchDSL\Query\Joining\ParentIdQuery;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class ParentIdQueryTest extends TestCase
{
    public function testToArray(): void
    {
        $query = new ParentIdQuery('parent_id', 'child_type', ['foo' => 'bar']);

        static::assertSame(
            [
                'parent_id' => [
                    'id' => 'parent_id',
                    'type' => 'child_type',
                    'foo' => 'bar',
                ],
            ],
            $query->toArray(),
        );
    }
}
