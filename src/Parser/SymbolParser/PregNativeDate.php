<?php

namespace Popy\Calendar\Parser\SymbolParser;

use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Parser\SymbolParserInterface;
use Popy\Calendar\Parser\FormatParserInterface;
use Popy\Calendar\Parser\DateLexer\PregSimple;

/**
 * Implementation of the native DateTime timestamp/timezones formats using preg
 * lexers.
 */
class PregNativeDate implements SymbolParserInterface
{
    /**
     * @inheritDoc
     */
    public function parseSymbol(FormatToken $token, FormatParserInterface $parser)
    {
        if ($token->is('U')) {
            // U   Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)  See also time()
            return new PregSimple($token, '-?\d+');
        }

        if ($token->is('u')) {
            // u   Microseconds
            return new PregSimple($token, '\d{6}');
        }

        if ($token->is('e')) {
            // e   Timezone identifier (added in PHP 5.1.0)    Examples: UTC, GMT, Atlantic/Azores
            return new PregSimple($token, '\S.*?');
        }

        if ($token->is('I')) {
            // I (capital i)   Whether or not the date is in daylight saving time  1 if Daylight Saving Time, 0 otherwise.
            return new PregSimple($token, '\d');
        }

        if ($token->is('O')) {
            // O   Difference to Greenwich time (GMT) in hours Example: +0200
            return new PregSimple($token, '[+\-]\d{4}');
        }

        if ($token->is('P')) {
            // P   Difference to Greenwich time (GMT) with colon between hours and minutes (added in PHP 5.1.3)    Example: +02:00
            return new PregSimple($token, '[+\-]\d\d:\d\d');
        }

        if ($token->is('T')) {
            // T   Timezone abbreviation   Examples: EST, MDT ...
            return new PregSimple($token, '[A-Z]{1,4}');
        }

        if ($token->is('Z')) {
            // Z   Timezone offset in seconds. The offset for timezones west of UTC is always negative, and for those east of UTC is always positive.  -43200 through 50400
            return new PregSimple($token, '-?\d{1,5}');
        }
    }
}
