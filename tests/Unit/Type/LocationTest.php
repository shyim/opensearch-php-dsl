<?php declare(strict_types=1);

namespace OpenSearchDSL\Tests\Unit\Type;

use OpenSearchDSL\Type\Location;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class LocationTest extends TestCase
{
    public function testSetters(): void
    {
        $location = new Location(50, -70);

        static::assertSame(50.0, $location->getLat());
        static::assertSame(-70.0, $location->getLon());

        $location->setLat(100);
        $location->setLon(90);

        static::assertSame(100.0, $location->getLat());
        static::assertSame(90.0, $location->getLon());
    }
}
