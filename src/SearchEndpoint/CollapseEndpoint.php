<?php declare(strict_types=1);

namespace OpenSearchDSL\SearchEndpoint;

use OpenSearchDSL\BuilderInterface;

/**
 * Search collapse dsl endpoint.
 */
class CollapseEndpoint extends AbstractSearchEndpoint
{
    /**
     * Endpoint name
     */
    public const NAME = 'collapse';

    private ?BuilderInterface $collapse = null;

    public function normalize(): ?array
    {
        if ($this->collapse) {
            return $this->collapse->toArray();
        }

        return null;
    }

    public function add(BuilderInterface $builder, ?string $key = null): string
    {
        if ($this->collapse) {
            throw new \OverflowException('Only one collapse can be set');
        }

        $this->collapse = $builder;

        return '';
    }

    public function getAll(?string $boolType = null): array
    {
        return ['' => $this->getCollapse()];
    }

    public function getCollapse(): BuilderInterface
    {
        return $this->collapse;
    }
}
