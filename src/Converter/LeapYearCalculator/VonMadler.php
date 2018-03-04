<?php

namespace Popy\Calendar\Converter\LeapYearCalculator;

use Popy\Calendar\Converter\SimpleLeapYearCalculatorInterface;

/**
 * Von Madler implementation (because why not being wrong in a funny way).
 */
class VonMadler implements SimpleLeapYearCalculatorInterface
{
    /**
     * @inheritDoc
     */
    public function isLeapYear($year)
    {
        return !($year % 4) && ($year % 128);
    }
}
