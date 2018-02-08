<?php

namespace Popy\Calendar\Factory;

use Popy\Calendar\Calendar\ComposedCalendar;
use Popy\Calendar\Converter\AgnosticConverter;
use Popy\Calendar\Converter\UnixTimeConverter;
use Popy\Calendar\Converter\LeapYearCalculator;
use Popy\Calendar\Formater\Localisation;
use Popy\Calendar\Formater\SymbolFormater;
use Popy\Calendar\Formater\AgnosticFormater;
use Popy\Calendar\Parser\AgnosticParser;
use Popy\Calendar\Parser\ResultMapper;
use Popy\Calendar\Parser\FormatLexer;
use Popy\Calendar\Parser\FormatParser\PregExtendedNative;

/**
 * Helper factory to build a Gregogrian calendar.
 */
class GregorianCalendarFactory
{
    /**
     * Builds a gregorian calendar.
     *
     * @return ComposedCalendar
     */
    public function build()
    {
        $calc = new LeapYearCalculator\Modern(365, 1970);
        $locale = new Localisation\NativeHardcoded();

        $converter = new AgnosticConverter(new UnixTimeConverter\Chain([
            new UnixTimeConverter\StandardDateFactory(),
            new UnixTimeConverter\Date(),
            new UnixTimeConverter\TimeOffset(),
            new UnixTimeConverter\DateSolar($calc, 0),
            new UnixTimeConverter\GregorianCalendarMonthes($calc),
            new UnixTimeConverter\Iso8601Weeks($calc, 3),
            new UnixTimeConverter\Time(),
        ]));

        $symbolFormater = new SymbolFormater\Chain([
            new SymbolFormater\Litteral(),
            new SymbolFormater\StandardDate(),
            new SymbolFormater\StandardDateFragmented($locale),
            new SymbolFormater\StandardDateSolar(),
            new SymbolFormater\StandardDateTime(),
            new SymbolFormater\StandardRecursive(),
            new SymbolFormater\Litteral(true),
        ]);

        $formater = new AgnosticFormater(
            new FormatLexer\MbString(),
            $converter,
            $symbolFormater
        );

        $mappers = new ResultMapper\Chain([
            new ResultMapper\StandardDateFactory(),
            new ResultMapper\StandardDate(),
            new ResultMapper\StandardDateFragmented(),
            new ResultMapper\StandardDateSolar(),
            new ResultMapper\StandardDateTime(),
        ]);

        $parser = new AgnosticParser(
            new PregExtendedNative(),
            $mappers,
            $converter
        );

        return new ComposedCalendar($formater, $parser);
    }
}