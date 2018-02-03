<?php

namespace Popy\Calendar\Converter;

/**
 * DateTime retresentation handling a usual solar date system, whare a year is
 * the duration of a the earth's revolution around teh Sun, and a day the
 * duration of a earth rotation on itself.
 */
interface SolarTimeRepresentationInterface extends DateTimeRepresentationInterface
{
    /**
     * Gets year.
     *
     * @return integer
     */
    public function getYear();

    /**
     * Is a leap year.
     *
     * @return boolean
     */
    public function isLeapYear();

    /**
     * Gets day index (in the year).
     *
     * @return integer.
     */
    public function getDayIndex();

    /**
     * Get time informations.
     *
     * @return array<int>
     */
    public function getTime();
}
