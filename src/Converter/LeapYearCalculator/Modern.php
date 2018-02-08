<?php

namespace Popy\Calendar\Converter\LeapYearCalculator;

/**
 * Modern leap day implementation (the gregorian one).
 */
class Modern extends AbstractCalculator
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
