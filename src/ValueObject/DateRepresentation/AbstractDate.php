<?php

namespace Popy\Calendar\ValueObject\DateRepresentation;

use DateTimeZone;
use Popy\Calendar\ValueObject\TimeOffset;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * Minimal abstract implementatuon.
 */
abstract class AbstractDate implements DateRepresentationInterface
{
    /**
     * Unix time.
     *
     * @var integer|null
     */
    protected $unixTime;

    /**
     * Unix microtime.
     *
     * @var integer|null
     */
    protected $unixMicroTime;

    /**
     * Timezone.
     *
     * @var DateTimeZone
     */
    protected $timezone;

    /**
     * TimeZone offset used when building this date object.
     *
     * @var TimeOffset
     */
    protected $offset;

    /**
     * @inheritDoc
     */
    public function getUnixTime()
    {
        return $this->unixTime;
    }

    /**
     * @inheritDoc
     */
    public function getUnixMicroTime()
    {
        return $this->unixMicroTime;
    }

    /**
     * @inheritDoc
     */
    public function getOffset()
    {
        return $this->offset ?: new TimeOffset();
    }

    /**
     * @inheritDoc
     */
    public function getTimezone()
    {
        return $this->timezone ?: new DateTimeZone(date_default_timezone_get());
    }

    /**
     * @inheritDoc
     */
    public function withUnixTime($unixTime)
    {
        $res = clone $this;
        $res->unixTime = $unixTime;

        return $res;
    }

    /**
     * @inheritDoc
     */
    public function withUnixMicroTime($unixMicroTime)
    {
        $res = clone $this;
        $res->unixMicroTime = $unixMicroTime;

        return $res;
    }

    /**
     * @inheritDoc
     */
    public function withOffset(TimeOffset $offset)
    {
        $res = clone $this;
        $res->offset = $offset;

        return $res;
    }

    /**
     * @inheritDoc
     */
    public function withTimezone(DateTimeZone $timezone)
    {
        $res = clone $this;
        $res->timezone = $timezone;

        return $res;
    }
}
