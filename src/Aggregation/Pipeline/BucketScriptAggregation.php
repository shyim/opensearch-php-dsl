<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Aggregation\Pipeline;

use OpenSearchDSL\ScriptAwareTrait;

/**
 * Class representing Bucket Script Pipeline Aggregation.
 *
 * @see https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-pipeline-bucket-script-aggregation.html
 */
class BucketScriptAggregation extends AbstractPipelineAggregation
{
    use ScriptAwareTrait;

    /**
     * @param string|array{id: string, params?: array<string, mixed>}|null $script
     */
    public function __construct(string $name, array $bucketsPath, $script = null)
    {
        parent::__construct($name, $bucketsPath);

        $this->setScript($script);
    }

    public function getArray(): array
    {
        if (!$this->getScript()) {
            throw new \LogicException(
                sprintf(
                    '`%s` aggregation must have script set.',
                    $this->getName()
                )
            );
        }

        return [
            'buckets_path' => $this->getBucketsPath(),
            'script' => $this->getScript(),
        ];
    }

    public function getType(): string
    {
        return 'bucket_script';
    }
}
