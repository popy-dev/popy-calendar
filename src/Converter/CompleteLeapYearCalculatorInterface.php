<?php

namespace Popy\Calendar\Converter;

/**
 * Complete leap year calculator.
 */
interface CompleteLeapYearCalculatorInterface extends SimpleLeapYearCalculatorInterface
{
    /**
     * Determines year length, in days.
     *
     * @param integer $year
     *
     * @return integer
     */
    public function getYearLength($year);

    /**
     * Get year's first day index (since the start of the era).
     *
     * @param integer $year
     *
     * @return integer
     */
    public function getYearEraDayIndex($year);

    /**
     * Gets year & dayIndex (in that year) from an eraDayIndex
     *
     * @param integer $eraDayIndex
     *
     * @return array [$year, $dayIndex]
     */
    public function getYearAndDayIndexFromErayDayIndex($eraDayIndex);
}
