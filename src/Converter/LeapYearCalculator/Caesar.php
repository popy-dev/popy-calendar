<?php

namespace Popy\Calendar\Converter\LeapYearCalculator;

use Popy\Calendar\Converter\LeapYearCalculatorInterface;

/**
 * Julius Caesar implementation (julian calendar way). Imprecise, but that's
 * not somebody you should mess with.
 */
class Caesar implements LeapYearCalculatorInterface
{
    /**
     * @inheritDoc
     */
    public function isLeapYear($year)
    {
        return !($year % 4);
    }
}
