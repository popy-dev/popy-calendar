<?php

namespace Popy\Calendar\Converter\LeapYearCalculator;

use Popy\Calendar\Converter\LeapYearCalculatorInterface;

/**
 * Modern leap day implementation.
 */
class Modern implements LeapYearCalculatorInterface
{
    /**
     * @inheritDoc
     */
    public function isLeapYear($year)
    {
        return !($year % 4) && ($year % 100)
            || !($year % 400)
        ;
    }
}
