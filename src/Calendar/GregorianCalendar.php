<?php

namespace Popy\Calendar\Calendar;

use DateTimeZone;
use DateTimeImmutable;
use RuntimeException;
use DateTimeInterface;
use Popy\Calendar\CalendarInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * Basic GregorianCalendar implementation using native php formating.
 */
class GregorianCalendar implements CalendarInterface
{
    /**
     * @inheritDoc
     */
    public function format(DateTimeInterface $input, $format)
    {
        return $input->format($format);
    }

    /**
     * @inheritDoc
     */
    public function formatDateRepresentation(DateRepresentationInterface $input, $format)
    {
        throw new RuntimeException('Not implemented');
    }

    /**
     * @inheritDoc
     */
    public function parse($input, $format, DateTimeZone $timezone = null)
    {
        if ($timezone !== null) {
            return DateTimeImmutable::createFromFormat($format, $input, $timezone) ?: null;
        }

        return DateTimeImmutable::createFromFormat($format, $input) ?: null;
    }

    /**
     * @inheritDoc
     */
    public function parseToDateRepresentation($input, $format, DateTimeZone $timezone = null)
    {
        throw new RuntimeException('Not implemented');
    }
}
