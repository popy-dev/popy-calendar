<?php

namespace Popy\Calendar\Converter;

use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use Popy\Calendar\ConverterInterface;
use Popy\Calendar\Converter\TimeConverterInterface;
use Popy\Calendar\Converter\LeapYearCalculatorInterface;
use Popy\Calendar\ValueObject\TimeOffset;
use Popy\Calendar\ValueObject\DateRepresentation\SolarTime;
use Popy\Calendar\ValueObject\DateRepresentationInterface;
use Popy\Calendar\ValueObject\DateTimeRepresentationInterface;
use Popy\Calendar\ValueObject\DateSolarRepresentationInterface;
use Popy\Calendar\ValueObject\DateFragmentedRepresentationInterface;

/**
 * Abstract implementation of a convertor using a "Era start date" to calculate
 * solar years, days & time from a timestamp. The calculation works fine, but
 * the abstraction isn't nice.
 */
abstract class AbstractPivotalDateSolarYear implements ConverterInterface
{
    /**
     * Solar day duration on earth.
     */
    const SECONDS_PER_DAY = 24 * 3600;

    /**
     * Year number of era start.
     */
    const FIRST_YEAR = 1;

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
    public function __construct(LeapYearCalculatorInterface $calculator, TimeConverterInterface $timeConverter = null, DatePartsConverterInterface $partsConverter = null)
    {
        $this->calculator = $calculator;
        $this->timeConverter = $timeConverter;
        $this->partsConverter = $partsConverter;
    }

    /**
     * Get era starting timestamp.
     *
     * @return integer
     */
    abstract protected function getEraStart();

    /**
     * Instanciates a DateSolarRepresentationInterface.
     *
     * @see self::fromDateTimeInterface
     *
     * @param DateTimeInterface $input        Initial input.
     * @param integer           $year         Era solar year.
     * @param boolean           $isLeapYear   Is a leap year.
     * @param integer           $dayIndex     Day index.
     *
     * @return DateSolarRepresentationInterface
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

        // Use a timestamp relative to the first year and including timezone
        // offset
        $relativeTimestamp = $unixTime - $this->getEraStart()
            + $offset->getValue()
        ;

        $eraDayIndex = intval($relativeTimestamp / static::SECONDS_PER_DAY);
        $dayIndex = $eraDayIndex;
        $year = static::FIRST_YEAR;

        // Will exit once the negative year will be found
        while ($dayIndex < 0) {
            $dayCount = 365 + $this->calculator->isLeapYear($year - 1);

            $dayIndex += $dayCount;
            $year--;
        }

        while (true) {
            $dayCount = 365 + $this->calculator->isLeapYear($year);

            if ($dayIndex < $dayCount) {
                // $year and dayIndex found !
                break;
            }

            $dayIndex -= $dayCount;
            $year++;
        }

        $res = $this->buildDateRepresentation(
                $input,
                $year,
                $this->calculator->isLeapYear($year),
                $dayIndex
            )
            ->withUnixTime($unixTime)
            ->withUnixMicroTime($unixMicrotime)
            ->withOffset($offset)
            ->withTimezone($input->getTimezone())
        ;

        if ($res instanceof DateFragmentedRepresentationInterface) {
            $res = $res->withDateParts(
                $this->partsConverter->fromDayIndex($res, $dayIndex)
            );
        }

        if ($res instanceof DateTimeRepresentationInterface) {
            $remainingMicroSeconds = $unixMicrotime
                + ($relativeTimestamp % static::SECONDS_PER_DAY) * 1000000
            ;

            $res = $res->withTime(
                $this->timeConverter->fromMicroSeconds($remainingMicroSeconds)
            );
        }

        return $res;
    }

    /**
     * @inheritDoc
     */
    public function toDateTimeInterface(DateRepresentationInterface $input)
    {
        if (!$input instanceof DateSolarRepresentationInterface) {
            throw new InvalidArgumentException(sprintf(
                '%s->%s only supports DateSolarRepresentationInterface, %s given',
                get_class($this),
                __METHOD__,
                get_class($input)
            ));
        }

        $unixTime      = $input->getUnixTime();
        $unixMicroTime = $input->getUnixMicroTime();

        if (null === $unixTime) {
            $year = $input->getYear();
            $dayIndex = $input->getDayIndex();

            $sign = $year < 1 ? -1 : 1;

            // Determine dayIndex from fragmented representation if possible.
            if (null === $dayIndex && $input instanceof DateFragmentedRepresentationInterface) {
                $dayIndex = $this->partsConverter->toDayIndex($input, $input->getDateParts());
            }


            for ($i=min($year, static::FIRST_YEAR); $i < max($year, static::FIRST_YEAR); $i++) {
                $dayCount = 365 + $this->calculator->isLeapYear($i);
                $dayIndex += $sign * $dayCount;
            }


            if ($input instanceof DateTimeRepresentationInterface) {
                $microsec = $this->timeConverter->toMicroSeconds(
                    $input->getTime()
                );
            }

            $unixTime = $this->getEraStart()
                + ($dayIndex * self::SECONDS_PER_DAY)
                + intval($microsec / 1000000)
            ;
            $unixMicroTime = $microsec % 1000000;

            $unixTime -= $this->getOffsetFor($input, $unixTime)->getValue();
        }

        $timestamp = sprintf(
            '%s.%06d UTC',
            $unixTime,
            $unixMicroTime
        );

        return DateTimeImmutable::createFromFormat('U.u e', $timestamp)
            ->setTimezone($input->getTimezone())
        ;
    }

    /**
     * Gets offset from input.
     *
     * @param DateTimeInterface $input
     *
     * @return TimeOffset
     */
    protected function getOffsetFrom(DateTimeInterface $input)
    {
        return TimeOffset::buildFromDateTimeInterface($input);
    }

    /**
     * Search for the offset that have (or might) have been used for the input
     * date representation, trying to mirror which offset the "getOffsetFrom"
     * method returned.
     *
     * @param DateSolarRepresentationInterface $input
     * @param integer                          $timestamp Calculated offsetted timestamp
     *
     * @return TimeOffset
     */
    protected function getOffsetFor(DateTimeRepresentationInterface $input, $timestamp)
    {
        $offset = $input->getOffset();

        if (null !== $offset->getValue()) {
            return $offset;
        }

        // Looking for timezone offset matching the incomplete timestamp.
        // The LMT transition is skipped to mirror the behaviour of
        // DateTimeZone->getOffset()
        $previous = null;
        
        $offsets = $input->getTimezone()->getTransitions(
            $timestamp - self::SECONDS_PER_DAY,
            // Usually, $timestamp += self::SECONDS_PER_DAY should be enougth,
            // but for dates before 1900-01-01 timezones fallback to LMT that
            // we are trying to skip.
            max(0, $timestamp += self::SECONDS_PER_DAY)
        );

        // DateTimeZone
        if (false === $offsets) {
            return $offset
                ->withValue(
                    $input->getTimezone()->getOffset(
                        new DateTimeImmutable()
                    )
                )
            ;
        }

        foreach ($offsets as $info) {
            if (
                (!$previous || $previous['abbr'] !== 'LMT')
                && $timestamp - $info['offset'] < $info['ts']
            ) {
                break;
            }

            $previous = $info;
        }

        if ($previous === null) {
            return $offset;
        }

        return new TimeOffset(
            $previous['offset'],
            $previous['isdst'],
            $previous['abbr']
        );
    }
}
