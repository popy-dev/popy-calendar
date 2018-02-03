<?php

namespace Popy\Calendar\Converter\LeapYearCalculator;

use Popy\Calendar\Converter\LeapYearCalculatorInterface;

/**
 * No leap, used for old calendars not implementing a leap.
 */
class NoLeap implements LeapYearCalculatorInterface
{
    /**
     * @inheritDoc
     */
    public function isLeapYear($year)
    {
        return false;
    }
}
