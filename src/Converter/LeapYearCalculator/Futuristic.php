<?php

namespace Popy\Calendar\Converter\LeapYearCalculator;

use Popy\Calendar\Converter\SimpleLeapYearCalculatorInterface;

/**
 * Futuristic leap day implementation, more precise than the modern one,
 * but not officially used.
 */
class Futuristic implements SimpleLeapYearCalculatorInterface
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
