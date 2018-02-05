<?php

namespace Popy\Calendar\Parser\SymbolParser;

use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Parser\SymbolParserInterface;
use Popy\Calendar\Parser\FormatParserInterface;
use Popy\Calendar\Parser\DateLexer\PregSimple;
use Popy\Calendar\Parser\DateLexer\PregChoice;
use Popy\Calendar\Formater\LocalisationInterface;
use Popy\Calendar\Formater\Localisation\NativeHardcoded;

/**
 * Implementation of the native DateTime formats using preg lexers.
 */
class PregNative implements SymbolParserInterface
{
    /**
     * Localisation
     *
     * @var LocalisationInterface
     */
    protected $locale;

    /**
     * Class constructor.
     *
     * @param LocalisationInterface|null $locale
     */
    public function __construct(LocalisationInterface $locale = null)
    {
        $this->locale = $locale ?: new NativeHardcoded();
    }

    /**
     * @inheritDoc
     */
    public function parseSymbol(FormatToken $token, FormatParserInterface $parser)
    {
        if ($token->is('y')) {
            // y   A two digit representation of a year
            return new PregSimple($token, '\d\d');
        }

        if ($token->isOne('Y', 'o')) {
            // Y   A full numeric representation of a year, 4 digits
            // o   ISO-8601 week-numbering year. This has the same value as Y, except that if the ISO week number (W) belongs to the previous or next year, that year is used instead.
            
            return new PregSimple($token, '\d\d\d\d');
        }

        if ($token->is('L')) {
            // L   Whether it's a leap year
            return new PregSimple($token, '[01]');
        }

        if ($token->is('F')) {
            // F   A full textual representation of a month, such as January or March
            return $this->buildXNamesLexer('Month', $token);
        }

        if ($token->is('M')) {
            // M   A short textual representation of a month, three letters    Jan through Dec
            return $this->buildXNamesLexer('MonthShort', $token);
        }

        if ($token->is('m')) {
            // m   Numeric representation of a month, with leading zeros   01 through 12
            return new PregSimple($token, '\d\d');
        }

        if ($token->is('n')) {
            // n   Numeric representation of a month, without leading zeros
            return new PregSimple($token, '\d\d?');
        }

        if ($token->is('t')) {
            // t   Number of days in the given month
            return new PregSimple($token, '\d\d');
        }

        if ($token->is('d')) {
            // d   Day of the month, 2 digits with leading zeros   01 to 31
            return new PregSimple($token, '\d\d');
        }

        if ($token->is('j')) {
            // j   Day of the month without leading zeros  1 to 31
            return new PregSimple($token, '\d\d?');
        }

        if ($token->is('l')) {
            // l (lowercase 'L')   A full textual representation of the day of the week
            return $this->buildXNamesLexer('Day', $token);
        }

        if ($token->is('D')) {
            // D   A textual representation of a day, three letters
            return $this->buildXNamesLexer('DayShort', $token);
        }

        if ($token->is('S')) {
            // S   English ordinal suffix for the day of the month, 2 characters
            return $this->buildSuffixesLexer($token);
        }

        if ($token->is('w')) {
            // w   Numeric representation of the day of the week   0 (for Sunday) through 6 (for Saturday)
            return new PregSimple($token, '\d');
        }

        if ($token->is('z')) {
            // z   The day of the year (starting from 0)
            return new PregSimple($token, '\d{1,3}');
        }

        if ($token->is('N')) {
            // N   ISO-8601 numeric representation of the day of the week (added in PHP 5.1.0) 1 (for Monday) through 7 (for Sunday)
            return new PregSimple($token, '\d');
        }

        if ($token->is('W')) {
            // W   ISO-8601 week number of year, weeks starting on Monday
            return new PregSimple($token, '\d\d?');
        }

        if ($token->isOne('a', 'A')) {
            // a   Lowercase Ante meridiem and Post meridiem   am or pm
            // A   Uppercase Ante meridiem and Post meridiem   AM or PM
            return new PregSimple($token, '[apAP][mM]');
        }

        if ($token->is('B')) {
            // B   Swatch Internet time    000 through 999
            return new PregSimple($token, '\d\d\d');
        }

        if ($token->isOne('g', 'G')) {
            // g   12-hour format of an hour without leading zeros 1 through 12
            // G   24-hour format of an hour without leading zeros 0 through 23
            return new PregSimple($token, '\d\d');
        }

        if ($token->isOne('h', 'H')) {
            // h   12-hour format of an hour with leading zeros    01 through 12
            // H   24-hour format of an hour with leading zeros    00 through 23
            return new PregSimple($token, '\d\d');
        }

        if ($token->is('i')) {
            // i   Minutes with leading zeros  00 to 59
            return new PregSimple($token, '\d\d');
        }

        if ($token->is('s')) {
            // s   Seconds, with leading zeros 00 through 59
            return new PregSimple($token, '\d\d');
        }

        if ($token->is('u')) {
            // u   Microseconds
            return new PregSimple($token, '\d{6}');
        }

        if ($token->is('v')) {
            // u   Milliseconds
            return new PregSimple($token, '\d\d\d');
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
            return new PregSimple($token, '[A-Z]{1,3}');
        }

        if ($token->is('Z')) {
            // Z   Timezone offset in seconds. The offset for timezones west of UTC is always negative, and for those east of UTC is always positive.  -43200 through 50400
            return new PregSimple($token, '-?\d{1,5}');
        }

        if ($token->is('c')) {
            // c   ISO 8601 date (added in PHP 5)  2004-02-12T15:19:21+00:00
            return $parser->parseFormat('Y-m-d\TH:i:sP');
        }

        if ($token->is('r')) {
            // r   » RFC 2822 formatted date   Example: Thu, 21 Dec 2000 16:01:07 +0200
            return $parser->parseFormat('D, d M Y H:i:s P');
        }

        if ($token->is('U')) {
            // U   Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)  See also time()
            return new PregSimple($token, '-?\d+');
        }
    }

    /**
     * Builds a choice lexer based on a get*name localisation method.
     *
     * @param string      $x     Method name middle part.
     * @param FormatToken $token Token.
     *
     * @return PregChoice
     */
    protected function buildXNamesLexer($x, FormatToken $token)
    {
        $choices = [];
        $i = 0;

        while (null !== $label = $this->locale->{"get${x}Name"}($i++)) {
            $choices[] = $label;
        }

        return new PregChoice($token, $choices);
    }

    /**
     * Builds a choice lexer based on the getNumberOrdinalSuffix localisation
     * method.
     *
     * @param FormatToken $token Token.
     *
     * @return PregChoice
     */
    protected function buildSuffixesLexer(FormatToken $token)
    {
        $choices = [];
        $i = 1;
        $repetitions = 0;

        while (null !== $label = $this->locale->getNumberOrdinalSuffix($i++)) {
            if (!in_array($label, $choices)) {
                $choices[] = $label;
                $repetitions = 0;
            } elseif (++$repetitions > 5) {
                break;
            }
        }

        return new PregChoice($token, $choices);
    }
}
