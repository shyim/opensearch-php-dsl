<?php declare(strict_types=1);

namespace OpenSearchDSL\Collapse;

use OpenSearchDSL\BuilderInterface;
use OpenSearchDSL\ParametersTrait;

/**
 * Data holder for collapse api.
 */
class Collapse implements BuilderInterface
{
    use ParametersTrait;

    public function __construct(private string $field)
    {
    }

    public function getType(): string
    {
        return 'collapse';
    }

    public function toArray(): array
    {
        $output = $this->processArray();

        $output['field'] = $this->field;

        return $output;
    }
}
