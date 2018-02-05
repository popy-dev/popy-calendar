<?php

namespace Popy\Calendar\Converter;

use DateTimeInterface;
use Popy\Calendar\Converter\TimeConverter\DuoDecimalTime;
use Popy\Calendar\Converter\LeapYearCalculator\Modern;
use Popy\Calendar\Converter\DateTimeRepresentation\Standard;
use Popy\Calendar\Converter\DatePartsConverter\StandardMonthes;

class Gregorian extends AbstractPivotalDateSolarYear
{
    /**
     * Year number of era start.
     */
    const FIRST_YEAR = 1973;

    /**
     * Class constructor.
     *
     * @param LeapYearCalculatorInterface|null $calculator    Leap year calculator.
     * @param TimeConverterInterface|null      $timeConverter Time converter.
     */
    public function __construct(LeapYearCalculatorInterface $calculator = null, TimeConverterInterface $timeConverter = null, DatePartsConverterInterface $partsConverter = null)
    {
        parent::__construct(
            $calculator ?: new Modern(),
            $timeConverter ?: new DuoDecimalTime(),
            $partsConverter ?: new StandardMonthes()
        );
    }

    /**
     * @inheritDoc
     */
    protected function getEraStart()
    {
        // Picked 1973 as it started a monday.
        return 94690800;
    }

    /**
     * @inheritDoc
     */
    protected function buildDateRepresentation(DateTimeInterface $input, $year, $isLeapYear, $dayIndex)
    {
        return new Standard($year, $isLeapYear, $dayIndex);
    }
}
