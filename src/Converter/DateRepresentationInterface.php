<?php

namespace Popy\Calendar\Converter;

use DateTimeZone;

/**
 * Date internal representation. This is a value object, which MUST be
 * immutable.
 *
 * Keep in mind that this interface is only a date representation. The only real
 * time value is the UNIX time/microtime couple, which represents a single
 * moment of time, independant from timezone, DST, formats, and is expressed in
 * a SI unit.
 *
 * An instance may not know the time/microtime it represents, for instance
 * when it has been built by a parser.
 */
interface DateRepresentationInterface
{
    /**
     * Gets the unix time, if availanle.
     *
     * @return integer|null
     */
    public function getUnixTime();

    /**
     * Gets the date's unix microtime, if available.
     *
     * @return integer|null
     */
    public function getUnixMicroTime();

    /**
     * Gets time offset, if available.
     *
     * @return integer|null
     */
    public function getOffset();

    /**
     * Gets timezone.
     *
     * @return DateTimeZone
     */
    public function getTimezone();

    /**
     * Gets a new date instance having the input unix time.
     *
     * @param integer|null $unixTime
     *
     * @return static
     */
    public function withUnixTime($unixTime);

    /**
     * Gets a new date instance having the input unix microtime.
     *
     * @param integer|null $unixMicroTime
     *
     * @return static
     */
    public function withUnixMicroTime($unixMicroTime);

    /**
     * Gets a new date instance having the input offset.
     *
     * @param integer|null $offset
     *
     * @return static
     */
    public function withOffset($offset);

    /**
     * Gets a new date instance having the input timezone.
     *
     * @param DateTimeZone $offset
     *
     * @return static
     */
    public function withTimezone(DateTimeZone $timezone);
}
