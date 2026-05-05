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

use OpenSearchDSL\BuilderBag;
use OpenSearchDSL\BuilderInterface;

/**
 * @internal
 */
class BuilderBagTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests if bag knows if he has a builder.
     */
    public function testHas(): void
    {
        $bag = new BuilderBag();
        $fooBuilder = $this->getBuilder('foo');
        $builderName = $bag->add($fooBuilder);
        static::assertTrue($bag->has($builderName));
    }

    /**
     * Tests if bag can remove a builder.
     */
    public function testRemove(): void
    {
        $bag = new BuilderBag();
        $fooBuilder = $this->getBuilder('foo');
        $acmeBuilder = $this->getBuilder('acme');
        $fooBuilderName = $bag->add($fooBuilder);
        $acmeBuilderName = $bag->add($acmeBuilder);

        $bag->remove($fooBuilderName);

        static::assertFalse($bag->has($fooBuilderName), 'Foo builder should not exist anymore.');
        static::assertTrue($bag->has($acmeBuilderName), 'Acme builder should exist.');
        static::assertCount(1, $bag->all());
    }

    /**
     * Tests if bag can clear it's builders.
     */
    public function testClear(): void
    {
        $bag = new BuilderBag(
            [
                $this->getBuilder('foo'),
                $this->getBuilder('baz'),
            ]
        );

        $bag->clear();

        static::assertEmpty($bag->all());
    }

    /**
     * Tests if bag can get a builder.
     */
    public function testGet(): void
    {
        $bag = new BuilderBag();
        $bazBuilder = $this->getBuilder('baz');
        $builderName = $bag->add($bazBuilder);

        static::assertEmpty($bag->get($builderName)->toArray());
    }

    /**
     * Returns builder.
     *
     * @param string $name
     *
     * @return BuilderInterface
     */
    private function getBuilder($name, array $data = [])
    {
        $friendlyBuilderMock = $this->getMockBuilder(BuilderInterface::class)
            ->onlyMethods(['toArray', 'getType'])
            ->addMethods(['getName'])
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $friendlyBuilderMock
            ->expects(static::any())
            ->method('getName')
            ->willReturn($name)
        ;

        $friendlyBuilderMock
            ->expects(static::any())
            ->method('toArray')
            ->willReturn($data)
        ;

        return $friendlyBuilderMock;
    }

    public function testAddWithoutName(): void
    {
        $bag = new BuilderBag();
        $bag->add(new class implements BuilderInterface {
            public function toArray(): array
            {
                return [];
            }

            public function getType(): string
            {
                return '';
            }
        });

        static::assertSame([], $bag->toArray());
    }

    public function testGetOutOfBounds(): void
    {
        static::expectException(\OutOfBoundsException::class);

        $bag = new BuilderBag();
        $bag->get('test');
    }

    public function testConstructor(): void
    {
        $bag = new BuilderBag(
            [
                $this->getBuilder('foo', ['a' => 1]),
                $this->getBuilder('baz', ['b' => 2]),
            ]
        );

        static::assertCount(2, $bag->all());
        static::assertEmpty($bag->all('foo'));

        static::assertSame(
            [
                'a' => 1,
                'b' => 2,
            ],
            $bag->toArray()
        );
    }
}
