<?php

namespace Popy\Calendar\Converter\UnixTimeConverter;

use Popy\Calendar\Converter\Conversion;
use Popy\Calendar\Converter\UnixTimeConverterInterface;
use Popy\Calendar\Converter\LeapYearCalculatorInterface;
use Popy\Calendar\ValueObject\DateSolarRepresentationInterface;

/**
 * Handles DateSolarRepresentationInterface.
 *
 * The eraDayIndex is calculated using an "eraStart" reference date, then the
 * year is calculated from the $firstYear property.
 */
class DateSolar implements UnixTimeConverterInterface
{
    /**
     * Era start unix time.
     *
     * @var integer
     */
    protected $eraStart;

    /**
     * First year number.
     *
     * @var integer
     */
    protected $firstYear = 1;

    /**
     * Regular year duration, in days.
     *
     * @var integer
     */
    protected $yearLengthInDays = 365;

    /**
     * Day length in seconds.
     *
     * @var integer
     */
    protected $dayLengthInSeconds = 24 * 3600;

    /**
     * Leap year calculator.
     *
     * @var LeapYearCalculatorInterface
     */
    protected $calculator;

    /**
     * Class constructor.
     *
     * @param LeapYearCalculatorInterface $calculator         Leap year calculator.
     * @param integer                     $eraStart           Era start unix time.
     * @param integer|null                $firstYear          First year number.
     * @param integer|null                $yearLengthInDays   Regular year duration, in days.
     * @param integer|null                $dayLengthInSeconds Day length in seconds.
     */
    public function __construct(LeapYearCalculatorInterface $calculator, $eraStart, $firstYear = null, $yearLengthInDays = null, $dayLengthInSeconds = null)
    {
        $this->calculator = $calculator;
        $this->eraStart   = $eraStart;

        if (null !== $firstYear) {
            $this->firstYear = $firstYear;
        }

        if (null !== $yearLengthInDays) {
            $this->yearLengthInDays = $yearLengthInDays;
        }

        if (null !== $dayLengthInSeconds) {
            $this->dayLengthInSeconds = $dayLengthInSeconds;
        }
    }

    /**
     * @inheritDoc
     */
    public function fromUnixTime(Conversion $conversion)
    {
        if (!$conversion->getTo() instanceof DateSolarRepresentationInterface) {
            return;
        }

        $res = $conversion->getTo();

        // Relative time from era start.
        $relativeTime = $conversion->getUnixTime() - $this->eraStart;

        // Calculating global day index. Floor is used to properly handle
        // negative values
        $eraDayIndex = (int)floor($relativeTime / $this->dayLengthInSeconds);

        $conversion->setUnixTime(
            $relativeTime
            - $eraDayIndex * $this->dayLengthInSeconds
        );

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

        $res = $res
            ->withYear($year, $this->calculator->isLeapYear($year))
            ->withDayIndex($dayIndex, $eraDayIndex)
        ;

        $conversion->setTo($res);
    }

    /**
     * @inheritDoc
     */
    public function toUnixTime(Conversion $conversion)
    {
        $input = $conversion->getTo() ?: $conversion->getFrom();

        if (!$input instanceof DateSolarRepresentationInterface) {
            return;
        }

        $year = $input->getYear();
        $dayIndex = $input->getDayIndex();

        $sign = $year < 1 ? -1 : 1;

        for ($i=min($year, $this->firstYear); $i < max($year, $this->firstYear); $i++) {
            $dayIndex += $sign * $this->getYearLength($i);
        }

        $conversion->setUnixTime(
            $conversion->getUnixTime() + $this->eraStart
            + $dayIndex * $this->dayLengthInSeconds
        );
    }

    protected function getYearLength($year)
    {
        return $this->yearLengthInDays + $this->calculator->isLeapYear($year);
    }
}
