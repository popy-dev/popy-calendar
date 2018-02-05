<?php

namespace Popy\Calendar\ValueObject;

/**
 * DateTime retresentation handling a usual solar date system, where a year is
 * the duration of a the earth's revolution around teh Sun, and a day the
 * duration of a earth rotation on itself.
 *
 * TODO : 
 *  - setters
 *  - eraDayIndex
 */
interface DateSolarRepresentationInterface extends DateRepresentationInterface
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
}
