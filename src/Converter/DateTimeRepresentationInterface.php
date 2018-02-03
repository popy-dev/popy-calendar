<?php

namespace Popy\Calendar\Converter;

use DateTimeZone;

/**
 * DateTime internal representation. This is a value object, expected to be
 * immutable.
 *
 * Keep in mind that this class is only a date representation. The only "real"
 * time value is the timestamp, which is meant to represent a single moment of
 * time, independant from timezone, DST, etc etc.
 * 
 * An instance may not know the timestamp it represents, for instance when it
 * has been built by a parser.
 */
interface DateTimeRepresentationInterface
{
    /**
     * Gets the timestamp, if available.
     *
     * @return integer|null
     */
    public function getTimestamp();

    /**
     * Gets timezone.
     *
     * @return DateTimeZone
     */
    public function getTimezone();

    /**
     * Gets time offset, if available.
     *
     * @return integer|null
     */
    public function getOffset();
}
