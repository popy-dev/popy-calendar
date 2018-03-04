<?php

namespace Popy\Calendar\Converter;

/**
 * Simple leap year calculator.
 */
interface SimpleLeapYearCalculatorInterface
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
