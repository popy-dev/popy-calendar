<?php

namespace Popy\Calendar\ValueObject;

/**
 * DateTime retresentation handling a usual solar date system, where a year is
 * the duration of a the earth's revolution around teh Sun, and a day the
 * duration of a earth rotation on itself. Works for other planets & stars.
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
     * @return integer|null
     */
    public function getYear();

    /**
     * Is a leap year.
     *
     * @return boolean|null
     */
    public function isLeapYear();

    /**
     * Gets day index (in the year).
     *
     * @return integer|null
     */
    public function getDayIndex();

    /**
     * Gets era day index.
     *
     * @return integer|null
     */
    public function getEraDayIndex();

    /**
     * Gets a new instance with input year & leap
     *
     * @param integer|null $year
     * @param boolean|null $isLeap
     *
     * @return static
     */
    public function withYear($year, $isLeap);

    /**
     * Gets a new instance with inpu indexes.
     *
     * @param integer|null $dayIndex
     * @param integer|null $eraDayIndex
     *
     * @return static
     */
    public function withDayIndex($dayindex, $eraDayIndex);
}
