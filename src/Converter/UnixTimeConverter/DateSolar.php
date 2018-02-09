<?php

namespace Popy\Calendar\Converter\UnixTimeConverter;

use Popy\Calendar\Converter\Conversion;
use Popy\Calendar\Converter\UnixTimeConverterInterface;
use Popy\Calendar\Converter\LeapYearCalculatorInterface;
use Popy\Calendar\ValueObject\DateSolarRepresentationInterface;

/**
 * Handles DateSolarRepresentationInterface.
 *
 * The eraDayIndex is calculated using an "eraStart" reference date. Year, year
 * length and leap days calculations are delegated to a LeapYearCalculator.
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
     * @param integer|null                $dayLengthInSeconds Day length in seconds.
     */
    public function __construct(LeapYearCalculatorInterface $calculator, $eraStart, $dayLengthInSeconds = null)
    {
        $this->calculator = $calculator;
        $this->eraStart   = $eraStart;

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

        if (is_integer($this->dayLengthInSeconds) || !function_exists('bc_sub')) {
            // bc not needed/not available

            // Relative time from era start.
            $relativeTime = $conversion->getUnixTime() - $this->eraStart;

            // Calculating global day index. Floor is used to properly handle
            // negative values
            $eraDayIndex = (int)floor($relativeTime / $this->dayLengthInSeconds);

            $conversion->setUnixTime(
                $relativeTime
                - $eraDayIndex * $this->dayLengthInSeconds
            );
        } else {
            // Using bc math if available for non int dayLengthInSeconds in
            // order to stay as precise as possible.

            // Relative time from era start.
            $relativeTime = bcsub($conversion->getUnixTime(), $this->eraStart);

            // Calculating global day index. Floor is used to properly handle
            // negative values
            $eraDayIndex = (int)floor(
                bcdiv($relativeTime, $this->dayLengthInSeconds)
            );

            $conversion->setUnixTime(bcsub(
                $relativeTime,
                bcmul($eraDayIndex * $this->dayLengthInSeconds)
            ));
        }

        list($year, $dayIndex) = $this->calculator
            ->getYearAndDayIndexFromErayDayIndex($eraDayIndex)
        ;

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
        $input = $conversion->getTo();

        if (!$input instanceof DateSolarRepresentationInterface) {
            return;
        }

        $year = $input->getYear();
        $eraDayIndex = $input->getDayIndex()
            + $this->calculator->getYearEraDayIndex($year)
        ;

        $conversion->setUnixTime(
            $conversion->getUnixTime() + $this->eraStart
            + $eraDayIndex * $this->dayLengthInSeconds
        );
    }
}
