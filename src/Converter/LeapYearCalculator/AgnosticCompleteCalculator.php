<?php

namespace Popy\Calendar\Converter\LeapYearCalculator;

use Popy\Calendar\Converter\SimpleLeapYearCalculatorInterface;
use Popy\Calendar\Converter\CompleteLeapYearCalculatorInterface;

/**
 * Abstract calculator implementation, implementing every method but isLeapYear,
 * every method relying on it to produce their results.
 */
class AgnosticCompleteCalculator implements CompleteLeapYearCalculatorInterface
{
    /**
     * Simple leap year calculator.
     *
     * @var SimpleLeapYearCalculatorInterface
     */
    protected $calculator;

    /**
     * Regular year duration, in days.
     *
     * @var integer
     */
    protected $yearLengthInDays = 365;

    /**
     * First year number.
     *
     * @var integer
     */
    protected $firstYear = 1;

    /**
     * Class constructor.
     *
     * @param SimpleLeapYearCalculatorInterface $calculator       Decorated calculator
     * @param integer|null                      $yearLengthInDays Year length.
     * @param integer|null                      $firstYear        First year number.
     */
    public function __construct(SimpleLeapYearCalculatorInterface $calculator, $yearLengthInDays = null, $firstYear = null)
    {
        $this->calculator = $calculator;

        if (null !== $yearLengthInDays) {
            $this->yearLengthInDays = $yearLengthInDays;
        }

        if (null !== $firstYear) {
            $this->firstYear = $firstYear;
        }
    }

    /**
     * @inheritDoc
     */
    public function isLeapYear($year)
    {
        return $this->calculator->isLeapYear($year);
    }

    /**
     * @inheritDoc
     */
    public function getYearLength($year)
    {
        return $this->yearLengthInDays + (int)$this->isLeapYear($year);
    }

    /**
     * @inheritDoc
     */
    public function getYearEraDayIndex($year)
    {
        $index = 0;

        $sign = $year < $this->firstYear ? -1 : 1;

        for ($i=min($year, $this->firstYear); $i < max($year, $this->firstYear); $i++) {
            $index += $sign * $this->getYearLength($i);
        }

        return $index;
    }

    /**
     * @inheritDoc
     */
    public function getYearAndDayIndexFromErayDayIndex($eraDayIndex)
    {
        $dayIndex = $eraDayIndex;
        $year = $this->firstYear;

        // Handling negative years
        while ($dayIndex < 0) {
            $dayIndex += $this->getYearLength(--$year);
        }

        // Positive years
        while ($dayIndex >= $dayCount = $this->getYearLength($year)) {
            $dayIndex -= $dayCount;
            $year++;
        }

        return [$year, $dayIndex];
    }
}
