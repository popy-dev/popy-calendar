<?php

namespace Popy\Calendar\Converter\DateTimeRepresentation;

use DateTimeZone;
use Popy\Calendar\Converter\DateRepresentationInterface;

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
     * @var integer
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
        return $this->offset;
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
    public function withOffset($offset)
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
