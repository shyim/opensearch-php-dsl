<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Aggregation\Metric;

use OpenSearchDSL\Aggregation\AbstractAggregation;
use OpenSearchDSL\Aggregation\Type\MetricTrait;
use OpenSearchDSL\BuilderInterface;

/**
 * Top hits aggregation.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-metrics-top-hits-aggregation.html
 */
class TopHitsAggregation extends AbstractAggregation
{
    use MetricTrait;

    private ?int $size;

    private ?int $from;

    private array $sorts = [];

    public function __construct(string $name, ?int $size = null, ?int $from = null, ?BuilderInterface $sort = null)
    {
        parent::__construct($name);

        $this->setFrom($from);
        $this->setSize($size);

        if ($sort) {
            $this->addSort($sort);
        }
    }

    public function getFrom(): ?int
    {
        return $this->from;
    }

    public function setFrom(?int $from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return BuilderInterface[]
     */
    public function getSorts(): array
    {
        return $this->sorts;
    }

    /**
     * @param BuilderInterface[] $sorts
     */
    public function setSorts(array $sorts): self
    {
        $this->sorts = $sorts;

        return $this;
    }

    public function addSort(BuilderInterface $sort): void
    {
        $this->sorts[] = $sort;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function getArray()
    {
        $sortsOutput = null;
        $addedSorts = $this->getSorts();

        if ($addedSorts) {
            $sortsOutput = [];

            foreach ($addedSorts as $sort) {
                $sortsOutput[] = $sort->toArray();
            }
        }

        $output = \array_filter(
            [
                'sort' => $sortsOutput,
                'size' => $this->getSize(),
                'from' => $this->getFrom(),
            ],
            static fn ($val) => \is_array($val) || ($val || \is_numeric($val))
        );

        return $output;
    }

    public function getType(): string
    {
        return 'top_hits';
    }
}
