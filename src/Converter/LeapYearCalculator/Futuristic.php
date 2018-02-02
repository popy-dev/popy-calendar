<?php

namespace Popy\RepublicanCalendar\LeapYearCalculator;

use Popy\Calendar\Converter\LeapYearCalculatorInterface;

/**
 * Futuristic leap day implementation, more precise than the modern one,
 * but not officially used.
 */
class Futuristic implements LeapYearCalculatorInterface
{
    /**
     * @inheritDoc
     */
    public function isLeapYear($year)
    {
        return !($year % 4) && ($year % 100)
            || !($year % 400) && ($year % 2000)
            || !($year % 4000) && ($year % 20000)
        ;
    }
}
