<?php

namespace Popy\Calendar\Converter;

/**
 * Leap year calculator.
 */
interface LeapYearCalculatorInterface
{
    /**
     * Determines if input year is a leap year.
     *
     * @param integer $year
     *
     * @return boolean
     */
    public function isLeapYear($year);

    /**
     * Determines year length, in days.
     *
     * @param integer $year
     *
     * @return integer
     */
    public function getYearLength($year);

    /**
     * Get year's first day index (since the start of the era).
     *
     * @param integer $year
     *
     * @return integer
     */
    public function getYearEraDayIndex($year);

    /**
     * Gets year & dayIndex (in that year) from an eraDayIndex
     *
     * @param integer $eraDayIndex
     *
     * @return array [$year, $dayIndex]
     */
    public function getYearAndDayIndexFromErayDayIndex($eraDayIndex);
}
