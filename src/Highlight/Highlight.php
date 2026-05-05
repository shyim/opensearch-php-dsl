<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Highlight;

use OpenSearchDSL\BuilderInterface;
use OpenSearchDSL\ParametersTrait;

/**
 * Data holder for highlight api.
 */
class Highlight implements BuilderInterface
{
    use ParametersTrait;

    private array $fields = [];

    private array $tags = [];

    public function addField(string $name, array $params = []): self
    {
        $this->fields[$name] = $params;

        return $this;
    }

    public function setTags(array $preTags, array $postTags): self
    {
        $this->tags['pre_tags'] = $preTags;
        $this->tags['post_tags'] = $postTags;

        return $this;
    }

    public function getType(): string
    {
        return 'highlight';
    }

    public function toArray(): array
    {
        $output = $this->tags;

        $output = $this->processArray($output);

        foreach ($this->fields as $field => $params) {
            $output['fields'][$field] = count($params) ? $params : new \stdClass();
        }

        return $output;
    }
}
