<?php

namespace Popy\Calendar\Calendar;

use DateTimeImmutable;
use DateTimeInterface;
use Popy\Calendar\CalendarInterface;

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
    public function parse($input, $format)
    {
        return DateTimeImmutable::createFromFormat($format, $input) ?: null;
    }
}
