<?php

namespace Popy\Calendar\Converter\LeapYearCalculator;

use Popy\Calendar\Converter\LeapYearCalculatorInterface;

/**
 * Persian / Solar Hijri leap year calculator.
 *
 * @link https://en.wikipedia.org/wiki/Solar_Hijri_calendar#Solar_Hijri_algorithmic_calendar
 */
class Persian implements LeapYearCalculatorInterface
{
    /**
     * @inheritDoc
     */
    public function isLeapYear($year)
    {
        $cycle = $year % 2820;

        if ($cycle >= 21 * 128) {
            $cycle -= 21 * 128;
        } else {
            $cycle = $cycle % 128;
        }

        if ($cycle >= 29) {
            $cycle -= 29;
            if ($cycle >= 2*33) {
                $cycle -= 2 * 33;
            } else {
                $cycle = $cycle % 33;
            }
        }

        $cycle++;

        return $cycle > 1 && ($cycle % 4 === 1);
    }
}
