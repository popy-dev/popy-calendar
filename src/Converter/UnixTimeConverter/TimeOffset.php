<?php

namespace Popy\Calendar\Converter\UnixTimeConverter;

use DateTimeImmutable;
use Popy\Calendar\Converter\Conversion;
use Popy\Calendar\ValueObject\TimeOffset as TimeOffsetValue;
use Popy\Calendar\Converter\UnixTimeConverterInterface;

/**
 * Determines dates offset and applies it to unixTime.
 */
class TimeOffset implements UnixTimeConverterInterface
{
    /**
     * Day length in seconds.
     *
     * @var integer
     */
    protected $dayLengthInSeconds = 24 * 3600;

    /**
     * @inheritDoc
     */
    public function fromUnixTime(Conversion $conversion)
    {
        $offset = $conversion->getFrom()->getOffset();

        $conversion->setUnixTime(
            $conversion->getUnixTime() + $offset->getValue()
        );

        if (null !== $conversion->getTo()) {
            $conversion->setTo(
                $conversion->getTo()->withOffset($offset)
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function toUnixTime(Conversion $conversion)
    {
        $input = $conversion->getTo() ?: $conversion->getFrom();

        $unixTime = $conversion->getUnixTime();

        $offset = $this->getOffsetFor(
            $conversion->getTo() ?: $conversion->getFrom(),
            $unixTime
        );

        $conversion
            ->setUnixTime($unixTime - $offset->getValue())
            ->setTo($input->withOffset($offset))
        ;
    }

    /**
     * Search for the offset that have (or might) have been used for the input
     * date representation.
     *
     * @param DateRepresentationInterface $input
     * @param integer                     $timestamp Calculated offsetted timestamp
     *
     * @return TimeOffset
     */
    protected function getOffsetFor(DateRepresentationInterface $input, $timestamp)
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
            $timestamp - $this->dayLengthInSeconds,
            // Usually, $timestamp += $this->dayLengthInSeconds should be enougth,
            // but for dates before 1900-01-01 timezones fallback to LMT that
            // we are trying to skip.
            max(0, $timestamp += $this->dayLengthInSeconds)
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

        return new TimeOffsetValue(
            $previous['offset'],
            $previous['isdst'],
            $previous['abbr']
        );
    }
}
