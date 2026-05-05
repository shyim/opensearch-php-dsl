<?php declare(strict_types=1);

namespace OpenSearchDSL\Query\Joining;

use OpenSearchDSL\BuilderInterface;
use OpenSearchDSL\ParametersTrait;

/**
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-parent-id-query.html
 */
class ParentIdQuery implements BuilderInterface
{
    use ParametersTrait;

    private string $childType;

    private string $parentId;

    public function __construct(string $parentId, string $childType, array $parameters = [])
    {
        $this->childType = $childType;
        $this->parentId = $parentId;
        $this->setParameters($parameters);
    }

    public function toArray(): array
    {
        $query = [
            'id' => $this->parentId,
            'type' => $this->childType,
        ];
        $output = $this->processArray($query);

        return [$this->getType() => $output];
    }

    public function getType(): string
    {
        return 'parent_id';
    }
}
