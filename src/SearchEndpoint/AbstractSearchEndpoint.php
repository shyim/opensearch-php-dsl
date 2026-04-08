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
use OpenSearchDSL\ParametersTrait;
use OpenSearchDSL\Serializer\Normalizer\AbstractNormalizable;

/**
 * Abstract class used to define search endpoint with references.
 */
abstract class AbstractSearchEndpoint extends AbstractNormalizable
{
    use ParametersTrait;

    public const NAME = 'search';
    private const KEY_LENGTH = 30;
    private const DEFAULT_ORDER = 10;

    /**
     * @var BuilderInterface[]
     */
    private array $container = [];

    public function add(BuilderInterface $builder, ?string $key = null): string
    {
        if (!$key) {
            $key = bin2hex(random_bytes(self::KEY_LENGTH));
        }

        if (array_key_exists($key, $this->container)) {
            throw new \OverflowException(sprintf('Builder with %s name for endpoint has already been added!', $key));
        }

        $this->container[$key] = $builder;

        return $key;
    }

    public function addToBool(BuilderInterface $builder, ?string $boolType = null, ?string $key = null): string
    {
        throw new \BadFunctionCallException(sprintf("Endpoint %s doesn't support bool statements", static::NAME));
    }

    public function remove(string $key): self
    {
        if ($this->has($key)) {
            unset($this->container[$key]);
        }

        return $this;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->container);
    }

    public function get(string $key): ?BuilderInterface
    {
        if ($this->has($key)) {
            return $this->container[$key];
        }

        return null;
    }

    /**
     * @return BuilderInterface[]
     */
    public function getAll(?string $boolType = null): array
    {
        return $this->container;
    }

    public function getBool()
    {
        throw new \BadFunctionCallException(sprintf("Endpoint %s doesn't support bool statements", static::NAME));
    }

    abstract public function normalize(): ?array;

    public function getOrder(): int
    {
        return self::DEFAULT_ORDER;
    }
}
