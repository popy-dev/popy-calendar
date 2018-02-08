<?php

namespace Popy\Calendar\Parser\ResultMapper;

use DateTimeZone;
use Popy\Calendar\Parser\DateLexerResult;
use Popy\Calendar\ValueObject\TimeOffset;
use Popy\Calendar\Parser\ResultMapperInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * Maps standard format symbols to DateRepresentationInterface fields.
 */
class StandardDate implements ResultMapperInterface
{
    /**
     * @inheritDoc
     */
    public function map(DateLexerResult $parts, DateRepresentationInterface $date)
    {
        $offset = $this->determineOffset($parts, $date->getOffset());

        return $date
            // SI Units
            // U   Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)
            // u   Microseconds
            ->withUnixTime($parts->get('U'))
            ->withUnixMicroTime($parts->get('u'))

            // Offset & timezone
            ->withOffset($offset)
            ->withTimezone($this->determineTimezone(
                $parts,
                $offset,
                $date->getTimezone()
            ))
        ;
    }

    // Todo : get original offset ?
    protected function determineOffset(DateLexerResult $parts, TimeOffset $offset)
    {
        // Z   Timezone offset in seconds.
        if (null !== $value = $parts->get('Z')) {
            $value = (int)$value;
        } elseif (null !== $o = $parts->getFirst('O', 'P')) {
            // O   Difference to Greenwich time (GMT) in hours Example: +0200
            // P   Difference to Greenwich time (GMT) with colon between hours and minutes
            $o = str_replace(':', '', $o);
            $sign = substr($o, 0, 1);
            $hours = (int)substr($o, 1, 2);
            $minutes = (int)substr($o, 3, 2);

            $o = $hours * 60 + $minutes;
            $o = $o * 60;

            if ($sign === '-') {
                $o = -$o;
            }

            $value = $o;
        }

        if (null !== $value) {
            $offset = $offset->withValue($value);
        }

        if (null !== $t = $parts->get('I')) {
            $offset = $offset->withDst($t);
        }
        
        if (null !== $t = $parts->get('T')) {
            $offset = $offset->withAbbreviation($t);
        }

        return $offset;
    }

    /**
     * Determine date's timezone. If an offset has been found, Timezone has
     * no effect on the date parsing, but will have on the date display.
     *
     * @param DateLexerResult $parts   Date lexer results.
     * @param integer|null    $offset  Date offset if it has been found.
     * @param DateTimeZone    $inputTz Default timezone if any.
     *
     * @return DateTimeZone
     */
    protected function determineTimezone(DateLexerResult $parts, TimeOffset $offset, DateTimeZone $inputTz)
    {
        // e   Timezone identifier (added in PHP 5.1.0)    Examples: UTC, GMT, Atlantic/Azores
        // T   Timezone abbreviation   Examples: EST, MDT ...
        // O   Difference to Greenwich time (GMT) in hours Example: +0200
        // P   Difference to Greenwich time (GMT) with colon between hours and minutes (added in PHP 5.1.3)    Example: +02:00
        if (null !== $tz = $parts->getFirst('e', 'T', 'O', 'P')) {
            return new DateTimeZone($tz);
        }

        // Create a fixed timezone matching the offset.
        if (null !== $tz = $offset->buildTimeZone()) {
            return $tz;
        }

        if (null !== $inputTz) {
            return $inputTz;
        }

        // Fallback.
        return new DateTimeZone(date_default_timezone_get());
    }
}
