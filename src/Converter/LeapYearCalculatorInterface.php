<?php

namespace Popy\Calendar\Converter;

/**
 * Leap year calculator.
 *
 * Todo : provide another method calculating the number of leaps occured sinceyear
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
}
