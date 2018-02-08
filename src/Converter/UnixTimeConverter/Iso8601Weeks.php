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
    /**
     * Leap year calculator.
     *
     * @var LeapYearCalculatorInterface
     */
    protected $calculator;

    /**
     * First day of first year weekday index. 1970 started a thursday.
     *
     * @var integer
     */
    protected $firstYearDayIndex = 3;

    /**
     * Class constructor.
     *
     * @param LeapYearCalculatorInterface $calculator         Leap year calculator.
     * @param integer|null                $firstYearDayIndex  First day of first year weekday index.
     */
    public function __construct(LeapYearCalculatorInterface $calculator, $firstYearDayIndex = null)
    {
        $this->calculator = $calculator;

        if (null !== $firstYearDayIndex) {
            $this->firstYearDayIndex = $firstYearDayIndex;
        }
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
        $dayOfWeek = ($input->getEraDayIndex() + $this->firstYearDayIndex) % 7;

        // dayOfWeek may be negative is eraDayIndex was negative
        if ($dayOfWeek < 0) {
            $dayOfWeek += 7;
        }

        $weekIndex = null;

        // Get day yearl-index of the same week thursday
        $thursdayIndex = $input->getDayIndex() + 3 - $dayOfWeek;

        if ($thursdayIndex >= $this->calculator->getYearLength($year)) {
            $year++;
            $weekIndex = 0;

            $thursdayIndex -= $this->calculator->getYearLength($year);
        } else {
            if ($thursdayIndex < 0) {
                $year--;
                $thursdayIndex =
                    $this->calculator->getYearLength($year)
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
        $input = $conversion->getTo();

        if (
            !$input instanceof DateFragmentedRepresentationInterface
            || !$input instanceof DateSolarRepresentationInterface
        ) {
            return;
        }

        if (null !== $input->getDayIndex() && null !== $input->getYear()) {
            return ;
        }

        $year = $input->getDateParts()->getTransversal(0);
        $weekIndex = $input->getDateParts()->getTransversal(1);
        $dayOfWeek = $input->getDateParts()->getTransversal(2);

        if (null === $year || null === $weekIndex) {
            // Too imprecise to be worth
            return;
        }

        $startingEraDayIndex = $this->calculator->getYearEraDayIndex($year);

        // DoW of the first day of year
        $dow = ($startingEraDayIndex + $this->firstYearDayIndex) % 7;

        // walk until first thursday
        $eraDayIndex = $startingEraDayIndex + (10 - $dow) % 7;

        // Now eraDayIndex points on the thursday of the week #0. Lets go to the
        // weekIndex, and move to the dayOfWeek
        $eraDayIndex += $weekIndex * 7 + $dayOfWeek - 3;

        $dayIndex = $eraDayIndex - $startingEraDayIndex;

        if ($dayIndex < 0) {
            $year--;
            $eraDayIndex += $this->calculator->getYearLength($year);
            $dayIndex = $eraDayIndex - $startingEraDayIndex;
        } else {
            $yl = $this->calculator->getYearLength($year);

            if ($dayIndex >= $yl) {
                $year++;
                $dayIndex -= $yl;
                $eraDayIndex += $yl;
            }
        }

        $isLeapYear = $this->calculator->isLeapYear($year);

        $input = $input
            ->withYear($year, $isLeapYear)
            ->withDayIndex($dayIndex, $eraDayIndex)
        ;

        $conversion->setTo($input);
    }
}
