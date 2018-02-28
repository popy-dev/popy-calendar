<?php

namespace Popy\Calendar\Parser\SymbolParser;

use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Parser\SymbolParserInterface;
use Popy\Calendar\Parser\FormatParserInterface;
use Popy\Calendar\Parser\DateLexer\PregSimple;
use Popy\Calendar\Parser\DateLexer\PregChoice;
use Popy\Calendar\Formatter\LocalisationInterface;
use Popy\Calendar\Formatter\Localisation\NativeHardcoded;

/**
 * Implementation of the native DateTime month/days formats using preg lexers.
 */
class PregNativeDateFragmented implements SymbolParserInterface
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
        if ($token->is('o')) {
            // o   ISO-8601 week-numbering year. This has the same value as Y, except that if the ISO week number (W) belongs to the previous or next year, that year is used instead.
            return new PregSimple($token, '\d\d\d\d');
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
            return new PregSimple($token, '\d\d?');
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

        while (null !== $label = $this->locale->{'get' . $x . 'Name'}($i++)) {
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
        $i = 0;
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
