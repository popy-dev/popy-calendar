<?php

namespace Popy\Calendar\Converter\DateTimeRepresentation;

use DateTimeZone;
use Popy\Calendar\Converter\DateTimeRepresentationInterface;

/**
 * Minimal abstract implementatuon.
 */
abstract class AbstractDateTime implements DateTimeRepresentationInterface
{
    /**
     * Timestamp : actual and trusted time representation.
     *
     * @var integer|null
     */
    protected $timestamp;

    /**
     * Timezone.
     *
     * @var DateTimeZone
     */
    protected $timezone;

    /**
     * TimeZone offset used when building this date object.
     *
     * @var integer
     */
    protected $offset;

    /**
     * @inheritDoc
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @inheritDoc
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @inheritDoc
     */
    public function getOffset()
    {
        return $this->offset;
    }
}
