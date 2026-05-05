<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Query\Geo;

use OpenSearchDSL\BuilderInterface;
use OpenSearchDSL\ParametersTrait;

/**
 * Represents Elasticsearch "geo_bounding_box" query.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/query-dsl-geo-bounding-box-query.html
 */
class GeoBoundingBoxQuery implements BuilderInterface
{
    use ParametersTrait;

    private array $values;

    private string $field;

    public function __construct(string $field, array $values, array $parameters = [])
    {
        $this->field = $field;
        $this->values = $values;
        $this->setParameters($parameters);
    }

    public function toArray(): array
    {
        return [
            $this->getType() => $this->processArray([$this->field => $this->points()]),
        ];
    }

    public function getType(): string
    {
        return 'geo_bounding_box';
    }

    private function points(): array
    {
        if (count($this->values) === 2) {
            return [
                'top_left' => $this->values['top_left'],
                'bottom_right' => $this->values['bottom_right'],
            ];
        }
        if (count($this->values) === 4) {
            return [
                'top' => $this->values['top'],
                'left' => $this->values['left'],
                'bottom' => $this->values['bottom'],
                'right' => $this->values['right'],
            ];
        }

        throw new \LogicException('Geo Bounding Box filter must have 2 or 4 geo points set.');
    }
}
