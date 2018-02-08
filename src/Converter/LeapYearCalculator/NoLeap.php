<?php

namespace Popy\Calendar\Converter\LeapYearCalculator;

/**
 * No leap, used for old calendars not implementing a leap.
 */
class NoLeap extends AbstractCalculator
{
    /**
     * @inheritDoc
     */
    public function isLeapYear($year)
    {
        return false;
    }
}
