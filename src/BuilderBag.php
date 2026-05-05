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
 * Container for named builders.
 */
class BuilderBag
{
    private const LENGTH = 30;

    /**
     * @var BuilderInterface[]
     */
    private array $bag = [];

    /**
     * @param BuilderInterface[] $builders
     */
    public function __construct(array $builders = [])
    {
        foreach ($builders as $builder) {
            $this->add($builder);
        }
    }

    public function add(BuilderInterface $builder): string
    {
        $name = ($builder instanceof NamedBuilderInterface) ? $builder->getName() : bin2hex(random_bytes(self::LENGTH));

        $this->bag[$name] = $builder;

        return $name;
    }

    public function has(string $name): bool
    {
        return isset($this->bag[$name]);
    }

    public function remove(string $name): void
    {
        unset($this->bag[$name]);
    }

    public function clear(): void
    {
        $this->bag = [];
    }

    public function get(string $name): BuilderInterface
    {
        if (!isset($this->bag[$name])) {
            throw new \OutOfBoundsException(sprintf('The key %s does not exists', $name));
        }

        return $this->bag[$name];
    }

    /**
     * @return BuilderInterface[]
     */
    public function all(?string $type = null): array
    {
        return array_filter(
            $this->bag,
            static fn (BuilderInterface $builder) => $type === null || $builder->getType() === $type
        );
    }

    public function toArray(): array
    {
        $output = [];
        foreach ($this->all() as $builder) {
            $output = array_merge($output, $builder->toArray());
        }

        return $output;
    }
}
