<?php

namespace Popy\Calendar\Converter;

use DateTimeZone;

/**
 * DateTime internal representation. THis is a value object, expected to be
 * immutable.
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
