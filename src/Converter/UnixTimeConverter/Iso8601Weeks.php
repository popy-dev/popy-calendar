<?php

namespace Popy\Calendar\Converter\UnixTimeConverter;

use Popy\Calendar\Converter\Conversion;
use Popy\Calendar\Converter\UnixTimeConverterInterface;
use Popy\Calendar\Converter\LeapYearCalculatorInterface;
use Popy\Calendar\ValueObject\DateSolarRepresentationInterface;
use Popy\Calendar\ValueObject\DateFragmentedRepresentationInterface;

/**
 * Implementation of the standard (gregorian like) month calculation.
 */
class Iso8601Weeks implements UnixTimeConverterInterface
{
    public function __construct(LeapYearCalculatorInterface $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @inheritDoc
     */
    public function fromUnixTime(Conversion $conversion)
    {
        $input = $conversion->getTo();

        if (
            !$input instanceof DateFragmentedRepresentationInterface
            || !$input instanceof DateSolarRepresentationInterface
        ) {
            return;
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

        $dateParts = $input->getDateParts()->withTransversals([
            $year,
            $weekIndex,
            $dayOfWeek
        ]);


        $conversion->setTo($input->withDateParts($dateParts));
    }

    /**
     * @inheritDoc
     */
    public function toUnixTime(Conversion $conversion)
    {
        // Could do the reverse calculation to get index & year. Meh.
        return;

        $input = $conversion->getTo();

        if (
            !$input instanceof DateFragmentedRepresentationInterface
            || !$input instanceof DateSolarRepresentationInterface
        ) {
            return;
        }

        if (null !== $input->getDayIndex()) {
            return ;
        }
    }
}
