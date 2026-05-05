<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\Highlight;

use OpenSearchDSL\Highlight\Highlight;

/**
 * @internal
 */
class HighlightTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests GetType method, it should return 'highlight'.
     */
    public function testGetType(): void
    {
        $highlight = new Highlight();
        $result = $highlight->getType();
        static::assertEquals('highlight', $result);
    }

    /**
     * Tests ParametersTrait hasParameter method.
     */
    public function testTraitHasParameter(): void
    {
        $highlight = new Highlight();
        $highlight->addParameter('_source', ['include' => ['title']]);
        $result = $highlight->hasParameter('_source');
        static::assertTrue($result);
    }

    /**
     * Tests ParametersTrait removeParameter method.
     */
    public function testTraitRemoveParameter(): void
    {
        $highlight = new Highlight();
        $highlight->addParameter('_source', ['include' => ['title']]);
        $highlight->removeParameter('_source');
        $result = $highlight->hasParameter('_source');
        static::assertFalse($result);
    }

    /**
     * Tests ParametersTrait getParameter method.
     */
    public function testTraitGetParameter(): void
    {
        $highlight = new Highlight();
        $highlight->addParameter('_source', ['include' => 'title']);
        $expectedResult = ['include' => 'title'];
        static::assertEquals($expectedResult, $highlight->getParameter('_source'));
    }

    /**
     * Tests ParametersTrait getParameters and setParameters methods.
     */
    public function testTraitSetGetParameters(): void
    {
        $highlight = new Highlight();
        static::assertSame($highlight, $highlight->setParameters(
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
        static::assertEquals($expectedResult, $highlight->getParameters());
    }

    /**
     * Test toArray method.
     */
    public function testToArray(): void
    {
        $highlight = new Highlight();
        $highlight->addField('ok');
        $highlight->addParameter('_source', ['include' => ['title']]);
        $highlight->setTags(['<tag>'], ['</tag>']);
        $result = $highlight->toArray();
        $expectedResult = [
            'fields' => [
                'ok' => new \stdClass(),
            ],
            '_source' => [
                'include' => [
                    'title',
                ],
            ],
            'pre_tags' => [
                '<tag>',
            ],
            'post_tags' => [
                '</tag>',
            ],
        ];
        static::assertEquals($expectedResult, $result);
    }
}
