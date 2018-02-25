<?php

namespace Popy\Calendar\Converter\UnixTimeConverter;

use BCMathExtended\BC;
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
     * @var integer|float|string
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
     * @param integer|float|string|null   $dayLengthInSeconds Day length in seconds.
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

        // TODO : remove code repetition by using a separate abstraction
        // handling basic math operations.

        if (is_integer($this->dayLengthInSeconds) || !class_exists(BC::class)) {
            // bc not needed/not available

            // Relative time from era start.
            $relativeTime = $conversion->getUnixTime() - $this->eraStart;

            // Calculating global day index. Floor is used to properly handle
            // negative values
            $eraDayIndex = (int)floor($relativeTime / $this->dayLengthInSeconds);

            $time = $relativeTime - $eraDayIndex * $this->dayLengthInSeconds;

            // TODO : handle the loss as microseconds ?
            if (is_float($time)) {
                $time = (int)ceil($time);
            }

            $conversion->setUnixTime($time);
        } else {
            // Using bc math if available for non int dayLengthInSeconds in
            // order to stay as precise as possible.

            // Relative time from era start.
            $relativeTime = bcsub($conversion->getUnixTime(), $this->eraStart);

            // Calculating global day index. Floor is used to properly handle
            // negative values
            $eraDayIndex = BC::floor(
                bcdiv($relativeTime, $this->dayLengthInSeconds)
            );

            // TODO : handle the loss as microseconds ?
            $time = BC::ceil(bcsub(
                $relativeTime,
                bcmul($eraDayIndex, $this->dayLengthInSeconds)
            ));

            $conversion->setUnixTime((int)$time);
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

        // TODO : BC Math implementation
        $conversion->setUnixTime(intval(
            $conversion->getUnixTime() + $this->eraStart
            + $eraDayIndex * $this->dayLengthInSeconds
        ));
    }
}
