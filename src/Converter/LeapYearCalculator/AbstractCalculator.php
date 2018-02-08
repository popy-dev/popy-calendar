<?php

namespace Popy\Calendar\Converter\LeapYearCalculator;

use Popy\Calendar\Converter\LeapYearCalculatorInterface;

/**
 * Abstract calculator implementation, implementing every method but isLeapYear,
 * every method relying on it to produce their results.
 */
abstract class AbstractCalculator implements LeapYearCalculatorInterface
{
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
     * @param integer|null $yearLengthInDays Year length.
     * @param integer|null $firstYear        First year number.
     */
    public function __construct($yearLengthInDays = null, $firstYear = null)
    {
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
