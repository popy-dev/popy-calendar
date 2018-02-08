<?php

namespace Popy\Calendar\Converter\LeapYearCalculator;

/**
 * Von Madler implementation (because why not being wrong in a funny way).
 */
class VonMadler extends AbstractCalculator
{
    /**
     * @inheritDoc
     */
    public function isLeapYear($year)
    {
        return !($year % 4) && ($year % 128);
    }
}
