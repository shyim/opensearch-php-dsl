<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\SearchEndpoint;

use OpenSearchDSL\BuilderInterface;

/**
 * Search highlight dsl endpoint.
 */
class HighlightEndpoint extends AbstractSearchEndpoint
{
    /**
     * Endpoint name
     */
    public const NAME = 'highlight';

    private ?BuilderInterface $highlight = null;

    public function normalize(): ?array
    {
        if ($this->highlight) {
            return $this->highlight->toArray();
        }

        return null;
    }

    public function add(BuilderInterface $builder, ?string $key = null): string
    {
        if ($this->highlight) {
            throw new \OverflowException('Only one highlight can be set');
        }

        $this->highlight = $builder;

        return '';
    }

    public function getAll(?string $boolType = null): array
    {
        return ['' => $this->getHighlight()];
    }

    public function getHighlight(): BuilderInterface
    {
        return $this->highlight;
    }
}
