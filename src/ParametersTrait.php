<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL;

/**
 * A trait which handles the behavior of parameters in queries, filters, etc.
 */
trait ParametersTrait
{
    private array $parameters = [];

    public function hasParameter(string $name): bool
    {
        return isset($this->parameters[$name]);
    }

    /**
     * @return static
     */
    public function removeParameter(string $name)
    {
        if ($this->hasParameter($name)) {
            unset($this->parameters[$name]);
        }

        return $this;
    }

    /**
     * @return array|string|int|float|bool|\stdClass
     */
    public function getParameter(string $name)
    {
        return $this->parameters[$name];
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array|string|int|float|bool|\stdClass|object $value
     *
     * @return static
     */
    public function addParameter(string $name, $value)
    {
        $this->parameters[$name] = $value;

        return $this;
    }

    /**
     * @return static
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    protected function processArray(array $array = []): array
    {
        return array_merge($array, $this->parameters);
    }
}
