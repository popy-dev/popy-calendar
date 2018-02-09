<?php

namespace Popy\Calendar\Converter\LeapYearCalculator;

/**
 * Float based calculator.
 */
class FloatBased extends AbstractCalculator
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
     * @param float        $yearLengthInDays Year length.
     * @param integer|null $firstYear        First year number.
     */
    public function __construct($yearLengthInDays, $firstYear = null)
    {
        $diff = explode('.', $yearLengthInDays);
        $diff[0] = '0';

        $this->remaining = implode('.', $diff);

        parent::__construct(intval($yearLengthInDays), $firstYear);
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
