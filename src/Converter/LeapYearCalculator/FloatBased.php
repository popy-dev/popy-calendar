<?php

namespace Popy\Calendar\Converter\LeapYearCalculator;

use Popy\Calendar\Converter\SimpleLeapYearCalculatorInterface;

/**
 * Float based calculator.
 */
class FloatBased implements SimpleLeapYearCalculatorInterface
{
    /**
     * Remaining day part at the end of a regular year.
     *
     * @var string|float
     */
    protected $remaining;

    /**
     * Class constructor.
     *
     * @param float $yearLengthInDays Year length.
     */
    public function __construct($yearLengthInDays)
    {
        $diff = explode('.', $yearLengthInDays);
        $diff[0] = '0';

        $this->remaining = implode('.', $diff);
    }

    /**
     * @inheritDoc
     */
    public function isLeapYear($year)
    {
        if (function_exists('bcmul')) {
            return
                floor((float)bcmul($year - 1, $this->remaining))
                < floor((float)bcmul($year, $this->remaining))
            ;
        }

        return
            floor(($year - 1) * (float)$this->remaining)
            < floor($year * (float)$this->remaining)
        ;
    }
}
