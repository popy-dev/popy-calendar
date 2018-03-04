<?php

namespace Popy\Calendar\Converter\UnixTimeConverter;

use Popy\Calendar\Converter\SimpleLeapYearCalculatorInterface;
use Popy\Calendar\ValueObject\DateFragmentedRepresentationInterface;

/**
 * Implementation of the standard (gregorian like) month calculation.
 */
class GregorianCalendarMonthes extends AbstractDatePartsSolarSplitter
{
    /**
     * Leap year calculator.
     *
     * @var SimpleLeapYearCalculatorInterface
     */
    protected $calculator;

    /**
     * Class constructor.
     *
     * @param SimpleLeapYearCalculatorInterface $calculator Leap year calculator.
     */
    public function __construct(SimpleLeapYearCalculatorInterface $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @inheritDoc
     */
    protected function getAllFragmentSizes(DateFragmentedRepresentationInterface $input)
    {
        // $input->isLeapYear can't be trusted when parsing a date.
        $leap = $this->calculator->isLeapYear($input->getYear());

        return [
            [31, 28 + $leap, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]
        ];
    }
}
