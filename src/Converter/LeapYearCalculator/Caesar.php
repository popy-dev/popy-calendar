<?php

namespace Popy\Calendar\Converter\LeapYearCalculator;

use Popy\Calendar\Converter\SimpleLeapYearCalculatorInterface;

/**
 * Julius Caesar implementation (julian calendar way). Imprecise, but that's
 * not somebody you should mess with.
 */
class Caesar implements SimpleLeapYearCalculatorInterface
{
    /**
     * @inheritDoc
     */
    public function isLeapYear($year)
    {
        return !($year % 4);
    }
}
