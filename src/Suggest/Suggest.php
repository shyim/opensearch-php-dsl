<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Suggest;

use OpenSearchDSL\NameAwareTrait;
use OpenSearchDSL\NamedBuilderInterface;
use OpenSearchDSL\ParametersTrait;

class Suggest implements NamedBuilderInterface
{
    use NameAwareTrait;
    use ParametersTrait;

    private string $type;

    private string $text;

    private string $field;

    public function __construct(string $name, string $type, string $text, string $field, array $parameters = [])
    {
        $this->setName($name);
        $this->setType($type);
        $this->setText($text);
        $this->setField($field);
        $this->setParameters($parameters);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function toArray(): array
    {
        return [
            $this->getName() => [
                'text' => $this->getText(),
                $this->getType() => $this->processArray(['field' => $this->getField()]),
            ],
        ];
    }
}
