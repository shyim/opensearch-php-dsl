<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit;

use OpenSearchDSL\ParametersTrait;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class ParametersTraitTest extends TestCase
{
    private TestTraitClass $parametersTraitMock;

    protected function setUp(): void
    {
        $this->parametersTraitMock = new TestTraitClass();
    }

    /**
     * Tests addParameter method.
     */
    public function testGetAndAddParameter(): void
    {
        $this->parametersTraitMock->addParameter('acme', 123);
        static::assertEquals(123, $this->parametersTraitMock->getParameter('acme'));
        $this->parametersTraitMock->addParameter('bar', 321);
        static::assertEquals(321, $this->parametersTraitMock->getParameter('bar'));
        $this->parametersTraitMock->removeParameter('acme');
    }
}

class TestTraitClass
{
    use ParametersTrait;
}
