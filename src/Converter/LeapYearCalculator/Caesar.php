<?php

namespace Popy\Calendar\Converter\LeapYearCalculator;

/**
 * Julius Caesar implementation (julian calendar way). Imprecise, but that's
 * not somebody you should mess with.
 */
class Caesar extends AbstractCalculator
{
    /**
     * @inheritDoc
     */
    public function isLeapYear($year)
    {
        return !($year % 4);
    }
}
