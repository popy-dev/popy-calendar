<?php

namespace Popy\Calendar\Converter\LeapYearCalculator;

use Popy\Calendar\Converter\SimpleLeapYearCalculatorInterface;

/**
 * No leap, used for old calendars not implementing a leap.
 */
class NoLeap implements SimpleLeapYearCalculatorInterface
{
    /**
     * @inheritDoc
     */
    public function isLeapYear($year)
    {
        return false;
    }
}
