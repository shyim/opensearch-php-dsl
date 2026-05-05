<?php declare(strict_types=1);

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenSearchDSL\Aggregation\Bucketing;

use OpenSearchDSL\Aggregation\AbstractAggregation;
use OpenSearchDSL\Aggregation\Type\BucketingTrait;

/**
 * Class representing Histogram aggregation.
 *
 * @see https://goo.gl/hGCdDd
 */
class DateHistogramAggregation extends AbstractAggregation
{
    use BucketingTrait;

    protected ?string $calendarInterval = null;

    protected ?string $fixedInterval = null;

    protected ?string $timeZone = null;

    protected ?string $format = null;

    public function __construct(
        string $name,
        string $field,
        ?string $calendarInterval = null,
        ?string $fixedInterval = null,
        ?string $format = null,
        ?string $timeZone = null,
    ) {
        parent::__construct($name);

        $this->setField($field);
        $this->setCalendarInterval($calendarInterval);
        $this->setFixedInterval($fixedInterval);
        $this->setFormat($format);
        $this->setTimeZone($timeZone);
    }

    public function getCalendarInterval(): ?string
    {
        return $this->calendarInterval;
    }

    public function setCalendarInterval(?string $calendarInterval): self
    {
        $this->calendarInterval = $calendarInterval;

        return $this;
    }

    public function getFixedInterval(): ?string
    {
        return $this->fixedInterval;
    }

    public function setFixedInterval(?string $fixedInterval): self
    {
        $this->fixedInterval = $fixedInterval;

        return $this;
    }

    public function getTimeZone(): ?string
    {
        return $this->timeZone;
    }

    public function setTimeZone(?string $timeZone): self
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(?string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getArray()
    {
        if ($this->getCalendarInterval() === null && $this->getFixedInterval() === null) {
            throw new \LogicException('Date histogram aggregation must have field and calendar_interval or fixed_interval set.');
        }

        $out = [
            'field' => $this->getField(),
        ];

        if ($this->getCalendarInterval()) {
            $out['calendar_interval'] = $this->getCalendarInterval();
        }

        if ($this->getFixedInterval()) {
            $out['fixed_interval'] = $this->getFixedInterval();
        }

        if ($this->getTimeZone()) {
            $out['time_zone'] = $this->getTimeZone();
        }

        if ($this->getFormat()) {
            $out['format'] = $this->getFormat();
        }

        return $out;
    }

    public function getType(): string
    {
        return 'date_histogram';
    }
}
