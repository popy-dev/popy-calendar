<?php

namespace Popy\Calendar\Converter\DatePartsConverter;

use InvalidArgumentException;
use Popy\Calendar\ValueObject\DateParts;
use Popy\Calendar\Converter\LeapYearCalculatorInterface;
use Popy\Calendar\Converter\DatePartsConverterInterface;
use Popy\Calendar\ValueObject\DateSolarRepresentationInterface;
use Popy\Calendar\ValueObject\DateFragmentedRepresentationInterface;

/**
 * Implementation of the standard (gregorian like) month calculation.
 */
class Iso8601Weeks implements DatePartsConverterInterface
{
    protected $converter;

    public function __construct(DatePartsConverterInterface $converter, LeapYearCalculatorInterface $calculator)
    {
        $this->converter = $converter;
        $this->calculator = $calculator;
    }

    /**
     * @inheritDoc
     */
    public function fromDayIndex(DateFragmentedRepresentationInterface $input, $dayIndex)
    {
        $res = $this->converter->fromDayIndex($input, $dayIndex);

        if (!$input instanceof DateSolarRepresentationInterface) {
            return $res;
        }

        $year = $input->getYear();
        // Assuming the era starting year is 1970, it starts a Thursday.
        $dayOfWeek = ($input->getEraDayIndex() + 3) % 7;
        $weekIndex = null;

        // Get day yearl-index of the same week thursday
        $thursdayIndex = $input->getDayIndex() + 3 - $dayOfWeek;

        if ($thursdayIndex >= 365 + $input->isLeapYear()) {
            $year++;
            $weekIndex = 0;
        } else {
            if ($thursdayIndex < 0) {
                $year--;
                $thursdayIndex =
                    365 + $this->calculator->isLeapYear($year)
                    + $thursdayIndex
                ;
            }

            $weekIndex = intval($thursdayIndex / 7);
        }


        return $res->withTransversals([
            $year,
            $weekIndex,
            $dayOfWeek
        ]);
    }

    /**
     * @inheritDoc
     */
    public function toDayIndex(DateFragmentedRepresentationInterface $input, DateParts $parts)
    {
        return $this->converter->toDayIndex($input, $parts);
    }


    protected function getDayOfWeekFromIndex($index)
    {
        $index += 3;

        return $index % 7;
    }

}
