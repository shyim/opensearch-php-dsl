<?php declare(strict_types=1);

namespace OpenSearchDSL\Tests\Unit\Collapse;

use OpenSearchDSL\Collapse\Collapse;

/**
 * @internal
 */
class CollapseTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests GetType method, it should return 'collapse'.
     */
    public function testGetType(): void
    {
        $collapse = new Collapse('field_name');
        $result = $collapse->getType();
        static::assertEquals('collapse', $result);
    }

    /**
     * Tests ParametersTrait hasParameter method.
     */
    public function testTraitHasParameter(): void
    {
        $collapse = new Collapse('field_name');
        $collapse->addParameter('inner_hits', [
            [
                'name' => 'cheapest_field_name',
                'size' => 1,
                'sort' => ['price'],
            ],
        ]);
        $result = $collapse->hasParameter('inner_hits');
        static::assertTrue($result);
    }

    /**
     * Tests ParametersTrait removeParameter method.
     */
    public function testTraitRemoveParameter(): void
    {
        $collapse = new Collapse('field_name');
        $collapse->addParameter('inner_hits', [
            [
                'name' => 'cheapest_field_name',
                'size' => 1,
                'sort' => ['price'],
            ],
        ]);
        $collapse->removeParameter('inner_hits');
        $result = $collapse->hasParameter('inner_hits');
        static::assertFalse($result);
    }

    /**
     * Tests ParametersTrait getParameter method.
     */
    public function testTraitGetParameter(): void
    {
        $collapse = new Collapse('field_name');
        $collapse->addParameter('inner_hits', [
            [
                'name' => 'cheapest_field_name',
                'size' => 1,
                'sort' => ['price'],
            ],
        ]);
        $expectedResult = [
            [
                'name' => 'cheapest_field_name',
                'size' => 1,
                'sort' => ['price'],
            ],
        ];
        static::assertEquals($expectedResult, $collapse->getParameter('inner_hits'));
    }

    /**
     * Tests ParametersTrait getParameters and setParameters methods.
     */
    public function testTraitSetGetParameters(): void
    {
        $collapse = new Collapse('field_name');
        static::assertSame($collapse, $collapse->setParameters(
            [
                '_source',
                ['include' => 'title'],
                'content',
                ['force_source' => true],
            ]
        ));
        $expectedResult = [
            '_source',
            ['include' => 'title'],
            'content',
            ['force_source' => true],
        ];
        static::assertEquals($expectedResult, $collapse->getParameters());
    }

    /**
     * Test toArray method.
     */
    public function testToArray(): void
    {
        $collapse = new Collapse('field_name');
        $collapse->addParameter('inner_hits', [
            [
                'name' => 'cheapest_field_name',
                'size' => 1,
                'sort' => ['price'],
            ],
        ]);
        $result = $collapse->toArray();
        $expectedResult = [
            'field' => 'field_name',
            'inner_hits' => [
                [
                    'name' => 'cheapest_field_name',
                    'size' => 1,
                    'sort' => ['price'],
                ],
            ],
        ];
        static::assertEquals($expectedResult, $result);
    }
}
