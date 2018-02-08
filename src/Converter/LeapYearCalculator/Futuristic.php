<?php

namespace Popy\RepublicanCalendar\LeapYearCalculator;

/**
 * Futuristic leap day implementation, more precise than the modern one,
 * but not officially used.
 */
class Futuristic extends AbstractCalculator
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
