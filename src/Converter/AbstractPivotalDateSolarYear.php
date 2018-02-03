<?php

namespace Popy\Calendar\Converter;

use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use Popy\Calendar\ConverterInterface;
use Popy\Calendar\Converter\TimeConverterInterface;
use Popy\Calendar\Converter\LeapYearCalculatorInterface;

/**
 * Abstract implementation of a convertor using a "Era start date" to calculate
 * solar years, days & time from a timestamp. The calculation works fine, but
 * the abstraction isn't nice.
 */
abstract class AbstractPivotalDateSolarYear implements ConverterInterface
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
     * Time converter.
     *
     * @var TimeConverterInterface
     */
    protected $timeConverter;

    /**
     * Class constructor.
     *
     * @param LeapYearCalculatorInterface $calculator    Leap year calculator.
     * @param TimeConverterInterface      $timeConverter Time converter.
     */
    public function __construct(LeapYearCalculatorInterface $calculator, TimeConverterInterface $timeConverter)
    {
        $this->calculator = $calculator;
        $this->timeConverter = $timeConverter;
    }

    /**
     * Get era starting timestamp (year 1 begining)
     *
     * @return integer
     */
    abstract protected function getEraStart();

    /**
     * Instanciates a SolarTimeRepresentationInterface.
     *
     * @see self::fromDateTimeInterface
     *
     * @param DateTimeInterface $input        Initial input.
     * @param integer           $year         Era solar year.
     * @param integer           $dayIndex     Day index.
     * @param array<int>        $microseconds Time information.
     * @param integer           $offset       Used offset.
     *
     * @return SolarTimeRepresentationInterface
     */
    abstract protected function buildDateRepresentation(DateTimeInterface $input, $year, $dayIndex, array $time, $offset);

    /**
     * @inheritDoc
     */
    public function fromDateTimeInterface(DateTimeInterface $input)
    {
        $offset = $this->getOffsetFrom($input);

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

        $time = $this->timeConverter->fromMicroSeconds($microsec);

        return $this->buildDateRepresentation(
            $input,
            $year,
            $eraDayIndex,
            $time,
            $offset
        );
    }

    /**
     * @inheritDoc
     */
    public function toDateTimeInterface(DateTimeRepresentationInterface $input)
    {
        if (!$input instanceof SolarTimeRepresentationInterface) {
            throw new InvalidArgumentException(sprintf(
                '%s->%s only supports SolarTimeRepresentationInterface, %s given',
                get_class($this),
                __METHOD__,
                get_class($input)
            ));
        }

        $year = $input->getYear();
        $dayIndex = $input->getDayIndex();
        $microsec = $this->timeConverter->toMicroSeconds(
            $input->getTime()
        );

        $sign = $year < 1 ? -1 : 1;

        for ($i=min($year, 1); $i < max($year, 1); $i++) {
            $dayCount = 365 + $this->calculator->isLeapYear($i);
            $dayIndex += $sign * $dayCount;
        }

        $timestamp = $this->getEraStart()
            + ($dayIndex * self::SECONDS_PER_DAY)
            + intval($microsec / 1000000)
        ;
        $microseconds = $microsec % 1000000;


        $timestamp -= $this->getOffsetFor($input, $timestamp);

        $timestring = sprintf(
            '%s.%06d UTC',
            $timestamp,
            $microseconds
        );

        return DateTimeImmutable::createFromFormat('U.u e', $timestring)
            ->setTimezone($timezone)
        ;
    }

    /**
     * Gets offset from input.
     *
     * @param DateTimeInterface $input
     * 
     * @return integer
     */
    protected function getOffsetFrom(DateTimeInterface $input)
    {
        return intval($input->format('Z'));
    }

    /**
     * Search for the offset that have (or might) have been used for the input
     * date representation, trying to mirror which offset the "getOffsetFrom"
     * method returned.
     *
     * @param SolarTimeRepresentationInterface $input
     * @param integer                          $timestamp Calculated offsetted timestamp
     *
     * @return integer
     */
    protected function getOffsetFor(DateTimeRepresentationInterface $input, $timestamp)
    {
        if (null !== $offset = $input->getOffset()) {
            return $offset
        }

        // Looking for timezone offset matching the incomplete timestamp.
        // The LMT transition is skipped to mirror the behaviour of
        // DateTimeZone->getOffset()
        $offset = 0;
        $previous = null;
        $offsets = $input->getTimezone()->getTransitions(
            $timestamp - self::SECONDS_PER_DAY,
            // Usually, $timestamp += self::SECONDS_PER_DAY should be enougth,
            // but for dates before 1900-01-01 timezones fallback to LMT that
            // we are trying to skip.
            max(0, $timestamp += self::SECONDS_PER_DAY)
        );
        foreach ($offsets as $info) {
            if (
                (!$previous || $previous !== 'LMT')
                && $timestamp - $info['offset'] < $info['ts']
            ) {
                break;
            }

            $previous = $info['abbr'];

            $offset = $info['offset'];
        }

        return $offset;
    }
}
