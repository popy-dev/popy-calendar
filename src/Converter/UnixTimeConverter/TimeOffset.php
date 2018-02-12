<?php

namespace Popy\Calendar\Converter\UnixTimeConverter;

use DateTimeZone;
use DateTimeImmutable;
use Popy\Calendar\Converter\Conversion;
use Popy\Calendar\ValueObject\TimeOffset as TimeOffsetValue;
use Popy\Calendar\Converter\UnixTimeConverterInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

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

        $input = $this->getOffsetFor($input, $unixTime);

        $conversion
            ->setUnixTime($unixTime - $input->getOffset()->getValue())
            ->setTo($input)
        ;
    }

    /**
     * Search for the offset that have (or might) have been used for the input
     * date representation, and updates input date.
     *
     * @param DateRepresentationInterface $input
     * @param integer                     $timestamp Calculated offsetted timestamp
     *
     * @return DateRepresentationInterface
     */
    protected function getOffsetFor(DateRepresentationInterface $input, $timestamp)
    {
        $input = $this->extractAbbreviation($input);
        $offset = $input->getOffset();

        if (null !== $offset->getValue()) {
            return $input;
        }

        // Looking for timezone offset matching the incomplete timestamp.
        // The LMT transition is skipped to mirror the behaviour of
        // DateTimeZone->getOffset()
        $previous = null;
        
        $offsets = $input->getTimezone()->getTransitions(
            $timestamp - $this->dayLengthInSeconds,
            // Usually, $timestamp + $this->dayLengthInSeconds should be enougth,
            // but for dates before 1900-01-01 timezones fallback to LMT that
            // we are trying to skip.
            max(0, $timestamp + $this->dayLengthInSeconds)
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

        return $input->withOffset(new TimeOffsetValue(
            $previous['offset'],
            $previous['isdst'],
            $previous['abbr']
        ));
    }

    /**
     * Extract informations from timezone abbreviation.
     *
     * @param DateRepresentationInterface $input
     *
     * @return DateRepresentationInterface
     */
    protected function extractAbbreviation(DateRepresentationInterface $input)
    {
        $offset = $input->getOffset();

        if (null === $abbr = $offset->getAbbreviation()) {
            return $input;
        }

        $abbr = strtolower($abbr);
        $list = DateTimeZone::listAbbreviations();

        if (!isset($list[$abbr]) || empty($list[$abbr])) {
            return $input;
        }

        $list = $list[$abbr];

        $criterias = [
            'offset' => $offset->getValue(),
            'timezone_id' => $input->getTimezone()->getName(),
            'dst' => $offset->isDst(),
        ];

        foreach ($criterias as $key => $value) {
            if (null === $value) {
                continue;
            }
            $previous = $list;

            $list = array_filter($list, function ($infos) use ($key, $value) {
                return $value === $infos[$key];
            });

            if (empty($list)) {
                $list = $previous;
            }
        }

        $infos = reset($list);

        if (null === $offset->getValue()) {
            $offset = $offset->withValue($infos['offset']);
        }

        return $input
            ->withOffset($offset->withDst($infos['dst']))
            ->withTimezone(new DateTimeZone($infos['timezone_id']))
        ;
    }
}
