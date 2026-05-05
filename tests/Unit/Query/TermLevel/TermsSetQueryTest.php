<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Tests\Unit\Query\TermLevel;

use OpenSearchDSL\Query\TermLevel\TermsSetQuery;

/**
 * @internal
 */
class TermsSetQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests toArray().
     */
    public function testToArray(): void
    {
        $terms = ['php', 'c++', 'java'];
        $parameters = ['minimum_should_match_field' => 'required_matches'];
        $query = new TermsSetQuery('programming_languages', $terms, $parameters);
        $expected = [
            'terms_set' => [
                'programming_languages' => [
                    'terms' => ['php', 'c++', 'java'],
                    'minimum_should_match_field' => 'required_matches',
                ],
            ],
        ];

        static::assertEquals($expected, $query->toArray());
    }

    public function testItThrowsAaExceptionWhenMinimumShouldMatchFieldOrMinimumShouldMatchScriptIsNotGiven(): void
    {
        $message = 'Either minimum_should_match_field or minimum_should_match_script must be set.';
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        $terms = ['php', 'c++', 'java'];
        new TermsSetQuery('programming_languages', $terms, []);
    }
}
