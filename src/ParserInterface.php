<?php

namespace Popy\Calendar;

use DateTimeZone;
use DateTimeInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * A Parser is responsible to parse an input string, according to a format, in
 * order to build a Date matching the input representation as close as possible.
 *
 * If the Parser is related to a Formater, its format SHOULD be the same as the
 * related Formater.
 *
 * The Parser MAY support some name localization.
 */
interface ParserInterface
{
    /**
     * Parses a time string according to a specified format, then returns
     * DateTimeInterface or null if not able to match.
     *
     * @param string            $input  Input date string.
     * @param string            $format Date format
     * @param DateTimeZone|null $timezone
     *
     * @return DateTimeInterface|null
     */
    public function parse($input, $format, DateTimeZone $timezone = null);

    /**
     * Parses a time string according to a specified format, then returns
     * DateRepresentationInterface or null if not able to match.
     *
     * @param string            $input  Input date string.
     * @param string            $format Date format
     * @param DateTimeZone|null $timezone
     *
     * @return DateRepresentationInterface|null
     */
    public function parseToDateRepresentation($input, $format, DateTimeZone $timezone = null);
}
