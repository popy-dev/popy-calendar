<?php

namespace Popy\Calendar\Converter;

use DateTimeZone;
use DateTimeImmutable;
use DateTimeInterface;
use Popy\Calendar\Converter\LeapYearCalculatorInterface;
use Popy\Calendar\Converter\LeapYearCalculator\Modern;

/**
 * Abstract implementation of a convertor using a "Era start date" to calculate
 * solar years, days & time from a timestamp.
 */
abstract class AbstractPivotalDateSolarYear
{
    /**
     * Self-explanatory.
     */
    const SECONDS_PER_DAY = 24 * 3600;

    /**
     * Leap year calculator.
     *
     * @var LeapYearCalculatorInterface
     */
    protected $calculator;

    /**
     * Class constructor.
     *
     * @param LeapYearCalculatorInterface|null $calculator Leap year calculator.
     */
    public function __construct(LeapYearCalculatorInterface $calculator = null)
    {
        $this->calculator = $calculator ?: new Modern();
    }

    /**
     * Get era starting timestamp (year 1 begining)
     *
     * @return integer
     */
    abstract protected function getEraStart();

    /**
     * Helps converting from a DateTimeInterface by calculating year, day index,
     *    time (in milliseconds), and DST offset (in seconds)
     *
     * @param DateTimeInterface $input
     * 
     * @return array<int> [year, dayIndex, microtime, offset]
     */
    public function fromDateTimeInterface(DateTimeInterface $input)
    {
        $offset = intval($input->format('Z'));

        // Use a timestamp relative to the first year and including timezone offset
        $relativeTimestamp = $input->getTimestamp()
            - $this->getEraStart()
            + $offset
        ;

        $eraDayIndex = intval($relativeTimestamp / self::SECONDS_PER_DAY);
        $year = 1;

        // Will exit once the negative year will be found
        while ($eraDayIndex < 0) {
            $dayCount = 365 + $this->calculator->isLeapYear($year - 1);

            $eraDayIndex += $dayCount;
            $year--;
        }

        while (true) {
            $dayCount = 365 + $this->calculator->isLeapYear($year);

            if ($eraDayIndex < $dayCount) {
                // $year and dayIndex found !
                break;
            }

            $eraDayIndex -= $dayCount;
            $year++;
        }

        $remainingMicroSeconds = intval($input->format('u'))
            + ($relativeTimestamp % static::SECONDS_PER_DAY) * 1000000
        ;

        return [
            $year,
            $eraDayIndex,
            $remainingMicroSeconds,
            $offset,
        ];
    }

    /**
     * Builds a DateTime taking some parameters from a custom-era date
     *     representation.
     *
     * @param integer      $year     Custom era year.
     * @param integer      $dayIndex Custom era day index.
     * @param integer      $microsec Remaining microseconds in the day (defining
     *                               time)
     * @param DateTimeZone $timezone input date timezone.
     *
     * @return DateTimeImmutable
     */
    public function toDateTimeInterface($year, $dayIndex, $microsec, DateTimeZone $timezone)
    {
        $sign = $year < 1 ? -1 : 1;

        for ($i=min($year, 1); $i < max($year, 1); $i++) {
            $dayCount = 365 + $this->calculator->isLeapYear($i);
            $dayIndex += $sign * $dayCount;
        }

        $timestamp = $this->getEraStart()
            + ($dayIndex * self::SECONDS_PER_DAY)
            + intval($remainingMicroSeconds / 1000000)
        ;
        $microseconds = $remainingMicroSeconds % 1000000;

        // Looking for timezone offset matching the incomplete timestamp.
        // The LMT transition is skipped to mirror the behaviour of
        // DateTimeZone->getOffset()
        $offset = 0;
        $previous = null;
        $offsets = $timezone->getTransitions($timestamp - self::SECONDS_PER_DAY);
        foreach ($offsets as $info) {
            if (
                (!$previous || $previous['abbr'] !== 'LMT')
                && $timestamp - $info['offset'] < $info['ts']
            ) {
                break;
            }

            $previous = $info;

            $offset = $info['offset'];
        }

        $timestamp -= $offset;

        $timestring = sprintf(
            '%s.%06d UTC',
            $timestamp,
            $microseconds
        );

        return DateTimeImmutable::createFromFormat('U.u e', $timestring)
            ->setTimezone($timezone)
        ;
    }
}
