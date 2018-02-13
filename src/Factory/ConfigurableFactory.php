<?php

namespace Popy\Calendar\Factory;

use Popy\Calendar\Calendar\ComposedCalendar;
use Popy\Calendar\Converter\AgnosticConverter;
use Popy\Calendar\Converter\UnixTimeConverter;
use Popy\Calendar\Converter\LeapYearCalculator;
use Popy\Calendar\Formatter\Localisation;
use Popy\Calendar\Formatter\SymbolFormatter;
use Popy\Calendar\Formatter\NumberConverter;
use Popy\Calendar\Formatter\AgnosticFormatter;
use Popy\Calendar\Parser\AgnosticParser;
use Popy\Calendar\Parser\ResultMapper;
use Popy\Calendar\Parser\FormatLexer;
use Popy\Calendar\Parser\FormatParser;
use Popy\Calendar\Parser\SymbolParser;

class ConfigurableFactory
{
    /**
     * Available values for option "leap".
     *
     * @var array<string>
     */
    protected static $leap = [
        'noleap' => LeapYearCalculator\NoLeap::class,
        'none'   => LeapYearCalculator\NoLeap::class,
        'julian' => LeapYearCalculator\Caesar::class,
        'caesar' => LeapYearCalculator\Caesar::class,
        'modern'    => LeapYearCalculator\Modern::class,
        'gregorian' => LeapYearCalculator\Modern::class,
        'futuristic' => LeapYearCalculator\Futuristic::class,
        'persian' => LeapYearCalculator\Persian::class,
        'hijri'   => LeapYearCalculator\Persian::class,
        'von_madler' => LeapYearCalculator\VonMadler::class,
        'float' => LeapYearCalculator\FloatBased::class,
    ];

    /**
     * Available values for option "locale".
     *
     * @var array<string>
     */
    protected static $locale = [
        'native' => Localisation\NativeHardcoded::class,
    ];

    /**
     * Available values for option "month"
     *
     * @var array<string>
     */
    protected static $month = [
        'gregorian' => UnixTimeConverter\GregorianCalendarMonthes::class,
        'equal_length' => UnixTimeConverter\EqualLengthMonthes::class,
    ];

    /**
     * Available values for option "week"
     *
     * @var array<string>
     */
    protected static $week = [
        'iso' => UnixTimeConverter\Iso8601Weeks::class,
        'simple' => UnixTimeConverter\SimpleWeeks::class,
    ];

    /**
     * Available values for option "number"
     *
     * @var array<string>
     */
    protected static $number = [
        'two_digits' => NumberConverter\TwoDigitsYear::class,
        'roman' => NumberConverter\Roman::class,
        'rfc2550' => NumberConverter\RFC2550::class,
    ];

    /**
     * Available values for option "additional_symbol_parser"
     *
     * @var array<string>
     */
    protected static $additional_symbol_parser = [
        'none'    => false,
        'rfc2550' => SymbolParser\PregNativeRFC2550::class,
    ];

    /**
     * Builds a gregorian calendar.
     *
     * @return ComposedCalendar
     */
    public function build(array $options = array())
    {
        $options['leap'] = $this->buildLeapCalculator($options);
        $options['locale'] = $this->buildLocale($options);

        $options['converter'] = $this->buildConverter($options);

        $options['number_converter'] = $this->buildNumberConverter($options);

        $options['symbol_formatter'] = $this->buildSymbolFormatter($options);

        $options['lexer'] = $this->buildLexer($options);

        $options['formatter'] = $this->buildFormatter($options);

        $options['mapper'] = $this->buildMapper($options);

        $options['additional_symbol_parser'] = $this->buildAdditionalSymbolParser($options);

        $options['symbol_parser'] = $this->buildSymbolParser($options);

        $options['format_parser'] = $this->buildFormatParser($options);

        $options['parser'] = $this->buildParser($options);

        return new ComposedCalendar($options['formatter'], $options['parser']);
    }

    protected function buildLeapCalculator(array &$options)
    {
        $leap = $this->getOptionValueChoice(
            $options,
            'leap',
            static::$leap,
            'modern'
        );

        if (is_object($leap)) {
            return $leap;
        }

        return new $leap(
            $this->getOptionValue($options, 'year_length', 365),
            $this->getOptionValue($options, 'era_start_year', 1970)
        );
    }

    protected function buildLocale(array &$options)
    {
        $locale = $this->getOptionValueChoice(
            $options,
            'locale',
            static::$locale,
            'native'
        );

        if (is_object($locale)) {
            return $locale;
        }

        return new $locale();
    }

    public function buildConverter(array &$options)
    {
        return new AgnosticConverter(new UnixTimeConverter\Chain([
            new UnixTimeConverter\StandardDateFactory(),
            new UnixTimeConverter\Date(),
            new UnixTimeConverter\TimeOffset(),
            $this->buildSolar($options),
            $this->buildMonthes($options),
            $this->buildWeek($options),
            $this->buildTime($options),
        ]));
    }

    protected function buildSolar(array &$options)
    {
        return new UnixTimeConverter\DateSolar(
            $options['leap'],
            $this->getOptionValue($options, 'era_start', 0),
            $this->getOptionValue($options, 'day_length', false) ?: null
        );
    }

    protected function buildMonthes(array &$options)
    {
        $month = $this->getOptionValueChoice(
            $options,
            'month',
            static::$month,
            'gregorian'
        );

        if (is_object($month)) {
            return $month;
        }

        if ($month === UnixTimeConverter\EqualLengthMonthes::class) {
            return new $month($options['leap'], $this->getOptionValue($options, 'month_length'));
        }

        return new UnixTimeConverter\GregorianCalendarMonthes($options['leap']);
    }

    protected function buildTime(array &$options)
    {
        $ranges = $this->getOptionValue($options, 'time_ranges', false) ?: null;

        if ($ranges === 'duodecimal') {
            $ranges = null;
        }

        if ($ranges === 'decimal') {
            $ranges = [10, 100, 100, 1000, 1000];
        }

        return new UnixTimeConverter\Time(
            $ranges,
            $this->getOptionValue($options, 'day_length', false) ?: null
        );
    }

    protected function buildWeek(array &$options)
    {
        $week = $this->getOptionValueChoice(
            $options,
            'week',
            static::$week,
            'iso'
        );

        if (is_object($week)) {
            return $week;
        }

        if ($week === UnixTimeConverter\SimpleWeeks::class) {
            return new $week($this->getOptionValue($options, 'week_length'));
        }

        return new UnixTimeConverter\Iso8601Weeks(
            $options['leap'],
            $this->getOptionValue($options, 'era_start_day_index', 3)
        );
    }

    protected function buildNumberConverter(array &$options)
    {
        $number = $this->getOptionValueChoice(
            $options,
            'number',
            static::$number,
            'two_digits'
        );

        if (is_object($number)) {
            return $number;
        }

        if ($number === NumberConverter\TwoDigitsYear::class) {
            return new $number(
                $this->getOptionValue($options, 'number_converter_year', 2000),
                $this->getOptionValue($options, 'number_converter_late_fifty', true)
            );
        }

        return new $number();
    }

    protected function buildSymbolFormatter(array &$options)
    {
        return new SymbolFormatter\Chain([
            new SymbolFormatter\Litteral(),
            new SymbolFormatter\StandardDate(),
            new SymbolFormatter\StandardDateFragmented($options['locale']),
            new SymbolFormatter\StandardDateSolar($options['number_converter']),
            new SymbolFormatter\StandardDateTime(),
            new SymbolFormatter\StandardRecursive(),
            new SymbolFormatter\Litteral(true),
        ]);
    }

    protected function buildLexer(array &$options)
    {
        return new FormatLexer\MbString();
    }

    protected function buildFormatter(array &$options)
    {
        return new AgnosticFormatter(
            $options['lexer'],
            $options['converter'],
            $options['symbol_formatter']
        );
    }

    protected function buildMapper(array &$options)
    {
        return new ResultMapper\Chain([
            new ResultMapper\StandardDateFactory(),
            new ResultMapper\StandardDate(),
            new ResultMapper\StandardDateFragmented(),
            new ResultMapper\StandardDateSolar(),
            new ResultMapper\StandardDateTime(),
        ]);
    }

    protected function buildAdditionalSymbolParser(array &$options)
    {
        $parser = $this->getOptionValueChoice(
            $options,
            'additional_symbol_parser',
            static::$additional_symbol_parser,
            'none'
        );

        if (is_object($parser)) {
            return $parser;
        }

        if ($parser === false) {
            return null;
        }

        return new $parser($options['number_converter']);
    }

    protected function buildSymbolParser(array &$options)
    {
        $chain = [
            new SymbolParser\PregNativeDate(),
            new SymbolParser\PregNativeRecursive(),
            new SymbolParser\PregNativeDateSolar($options['number_converter']),
            new SymbolParser\PregNativeDateFragmented($options['locale']),
            new SymbolParser\PregNativeDateTime(),
        ];

        if ($options['additional_symbol_parser']) {
            array_unshift($chain, $options['additional_symbol_parser']);
        }

        return new SymbolParser\Chain($chain);
    }

    protected function buildFormatParser(array &$options)
    {
        return new FormatParser\PregExtendedNative(
            $options['lexer'],
            $options['symbol_parser']
        );
    }

    protected function buildParser(array &$options)
    {
        return new AgnosticParser(
            $options['format_parser'],
            $options['mapper'],
            $options['converter']
        );
    }

    protected function getOptionValue(array $options, $name, $default = null)
    {
        if (isset($options[$name])) {
            return $options[$name];
        }

        if ($default !== null) {
            return $default;
        }

        throw new InvalidArgumentException(sprintf(
            '"%s" option is required.',
            $name
        ));
    }

    protected function getOptionValueChoice(array $options, $name, $list, $default = null)
    {
        $value = $this->getOptionValue($options, $name, $default);

        if (is_object($value)) {
            return $value;
        }

        if (!isset($list[$value])) {
            throw new InvalidArgumentException(sprintf(
                'Invalid value "%s" for option "%s". possible values are : %s',
                $value,
                $name,
                implode(', ', array_keys($list))
            ));
        }

        return $list[$value];
    }
}
