<?php

namespace Popy\Calendar\ValueObject\DateRepresentation;

use DateTimeZone;
use DateTimeInterface;
use Popy\Calendar\Converter\Conversion;
use Popy\Calendar\ValueObject\TimeOffset;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * Basic implementation with static factory methods.
 */
class Date implements DateRepresentationInterface
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


    public static function buildFromUnixTime($time, DateTimeZone $timezone = null)
    {
        $res = new static();
        $res->unixTime = (int)$time;
        $res->timezone = $timezone;

        return $res;
    }

    public static function buildFromMicroTime($microtime, DateTimeZone $timezone = null)
    {
        list($micro, $time) = explode(' ', $microtime);

        $res = new static();
        $res->unixTime = (int)$time;
        $res->unixMicrotime = (int)substr($micro, 2, 6);
        $res->timezone = $timezone;

        return $res;
    }

    public static function buildFromDateTimeInterface(DateTimeInterface $datetime)
    {
        $res = new static();

        $res->unixTime = $datetime->getTimestamp();
        $res->unixMicroTime = (int)$datetime->format('u');
        $res->timezone = $datetime->getTimezone();
        $res->offset = TimeOffset::buildFromDateTimeInterface($datetime);

        return $res;
    }

    public static function buildFromTimezone(DateTimeZone $timezone = null)
    {
        $res = new static();
        $res->timezone = $timezone;

        return $res;
    }

    public static function fromRepresentation(DateRepresentationInterface $date)
    {
        $res = new static();

        $res->unixTime = $date->unixTime;
        $res->unixMicrotime = $date->unixMicrotime;
        $res->timezone = clone $date->timezone;
        $res->offset   = clone $date->offset;

        return $res;
    }

    public static function fromConversion(Conversion $conversion)
    {
        $res = new static();

        $res->unixTime = $conversion->getUnixTime();
        $res->unixMicrotime = $conversion->getUnixMicrotime();
        $res->timezone = clone $conversion->getTo()->getTimezone();
        $res->offset   = clone $conversion->getTo()->getOffset();

        return $res;
    }
}
