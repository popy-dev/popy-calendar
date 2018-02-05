<?php

namespace Popy\Calendar\Converter;

use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use Popy\Calendar\ConverterInterface;
use Popy\Calendar\Converter\TimeConverterInterface;
use Popy\Calendar\Converter\LeapYearCalculatorInterface;
use Popy\Calendar\Converter\DateTimeRepresentation\SolarTime;
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
     * Get era starting timestamp.
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
     * @param boolean           $isLeapYear   Is a leap year.
     * @param integer           $dayIndex     Day index.
     *
     * @return SolarTimeRepresentationInterface
     */
    protected function buildDateRepresentation(DateTimeInterface $input, $year, $isLeapYear, $dayIndex)
    {
        return new SolarTime($year, $isLeapYear, $dayIndex);
    }

    /**
     * @inheritDoc
     */
    public function fromDateTimeInterface(DateTimeInterface $input)
    {
        $unixTime = $input->getTimestamp();
        $unixMicrotime = (int)$input->format('u');
        $offset = $this->getOffsetFrom($input);


        // Use a timestamp relative to the first year and including timezone offset
        $relativeTimestamp = $unixTime - $this->getEraStart() + $offset;

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

        $remainingMicroSeconds = $unixMicrotime
            + ($relativeTimestamp % static::SECONDS_PER_DAY) * 1000000
        ;

        $res = $this->buildDateRepresentation(
            $input,
            $year,
            $this->calculator->isLeapYear($year),
            $eraDayIndex
        );

        return $res
            ->withTimezone($input->getTimezone())
            ->withUnixTime($unixTime)
            ->withUnixMicroTime((int)$input->format('u'))
            ->withOffset($offset)
            ->withTime(
                $this->timeConverter->fromMicroSeconds($remainingMicroSeconds)
            )
        ;
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

        $microseconds = null;
        $timestamp = $timestamp = $input->getTimestamp();

        if (null === $timestamp) {
            // If input doesn't contain its timestamp, we'll have to calculate it
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
        } elseif (null !== $microsec = $input->getMicroseconds()) {
            // A timestamp was available, but we also have microseconds.
            // We have to ckeck if they are SI microseconds before adding it.
            // TODO : should probably make microseconds part of the interface,
            // making them part of the date real value along with timestamp,
            // and introducing another format character for non-SI microsecs.
            $cmp = $this->timeConverter->toMicroSeconds([
                0,
                0,
                0,
                $microsec
            ]);

            if ($microsec === $cmp) {
                $microseconds = $microsec;
            }
        }

        $timestring = sprintf(
            '%s.%06d UTC',
            $timestamp,
            $microseconds
        );

        return DateTimeImmutable::createFromFormat('U.u e', $timestring)
            ->setTimezone($input->getTimezone())
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
            return $offset;
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
