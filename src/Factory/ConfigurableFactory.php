<?php

namespace Popy\Calendar\Factory;

use InvalidArgumentException;
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
    protected $leap = [
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
    protected $locale = [
        'native' => Localisation\NativeHardcoded::class,
    ];

    /**
     * Available values for option "month"
     *
     * @var array<string>
     */
    protected $month = [
        'gregorian' => UnixTimeConverter\GregorianCalendarMonthes::class,
        'equal_length' => UnixTimeConverter\EqualLengthMonthes::class,
    ];

    /**
     * Available values for option "week"
     *
     * @var array<string>
     */
    protected $week = [
        'iso' => UnixTimeConverter\Iso8601Weeks::class,
        'simple' => UnixTimeConverter\SimpleWeeks::class,
    ];

    /**
     * Available values for option "number"
     *
     * @var array<string>
     */
    protected $number = [
        'two_digits' => NumberConverter\TwoDigitsYear::class,
        'roman' => NumberConverter\Roman::class,
        'rfc2550' => NumberConverter\RFC2550::class,
    ];

    /**
     * Available values for option "additional_symbol_parser"
     *
     * @var array<string>
     */
    protected $additional_symbol_parser = [
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
        return new ComposedCalendar(
            $this->get('formatter', $options),
            $this->get('parser', $options)
        );
    }

    /**
     * Builds a date formatter.
     *
     * @return AgnosticFormatter
     */
    public function buildFormatter(array $options = array())
    {
        return $this->get('formatter', $options);
    }

    /**
     * Builds a date formatter.
     *
     * @return AgnosticParser
     */
    public function buildParser(array $options = array())
    {
        return $this->get('parser', $options);
    }

    /**
     * Builds a date converter.
     *
     * @return AgnosticConverter
     */
    public function buildConverter(array $options = array())
    {
        return $this->get('converter', $options);
    }

    /**
     * Generic service getter.
     *
     * @param string $service  Service name.
     * @param array  &$options Option array
     *
     * @return mixed
     */
    protected function get($service, array &$options)
    {
        if (isset($options[$service]) && is_object($options[$service])) {
            return $options[$service];
        }

        $service = explode('_', $service);
        $service = array_map('ucfirst', $service);
        $service = 'get' . implode('', $service);

        return $options[$service] = $this->$service($options);
    }

    protected function getLeap(array &$options)
    {
        $leap = $this->getOptionValueChoice(
            $options,
            'leap',
            $this->leap,
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

    protected function getLocale(array &$options)
    {
        $locale = $this->getOptionValueChoice(
            $options,
            'locale',
            $this->locale,
            'native'
        );

        if (is_object($locale)) {
            return $locale;
        }

        return new $locale();
    }

    protected function getConverter(array &$options)
    {
        return new AgnosticConverter(new UnixTimeConverter\Chain([
            new UnixTimeConverter\StandardDateFactory(),
            new UnixTimeConverter\Date(),
            new UnixTimeConverter\TimeOffset(),
            $this->get('solar', $options),
            $this->get('monthes', $options),
            $this->get('week', $options),
            $this->get('time', $options),
        ]));
    }

    protected function getSolar(array &$options)
    {
        return new UnixTimeConverter\DateSolar(
            $this->get('leap', $options),
            $this->getOptionValue($options, 'era_start', 0),
            $this->getOptionValue($options, 'day_length', false) ?: null
        );
    }

    protected function getMonthes(array &$options)
    {
        $month = $this->getOptionValueChoice(
            $options,
            'month',
            $this->month,
            'gregorian'
        );

        if (is_object($month)) {
            return $month;
        }

        if ($month === UnixTimeConverter\EqualLengthMonthes::class) {
            return new $month($this->get('leap', $options), $this->getOptionValue($options, 'month_length'));
        }

        return new UnixTimeConverter\GregorianCalendarMonthes($this->get('leap', $options));
    }

    protected function getTime(array &$options)
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

    protected function getWeek(array &$options)
    {
        $week = $this->getOptionValueChoice(
            $options,
            'week',
            $this->week,
            'iso'
        );

        if (is_object($week)) {
            return $week;
        }

        if ($week === UnixTimeConverter\SimpleWeeks::class) {
            return new $week($this->getOptionValue($options, 'week_length'));
        }

        return new UnixTimeConverter\Iso8601Weeks(
            $this->get('leap', $options),
            $this->getOptionValue($options, 'era_start_day_index', 3)
        );
    }

    protected function getNumberConverter(array &$options)
    {
        $number = $this->getOptionValueChoice(
            $options,
            'number',
            $this->number,
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

    protected function getSymbolFormatter(array &$options)
    {
        return new SymbolFormatter\Chain([
            new SymbolFormatter\Litteral(),
            new SymbolFormatter\StandardDate(),
            new SymbolFormatter\StandardDateFragmented($this->get('locale', $options)),
            new SymbolFormatter\StandardDateSolar($this->get('number_converter', $options)),
            new SymbolFormatter\StandardDateTime(),
            new SymbolFormatter\StandardRecursive(),
            new SymbolFormatter\Litteral(true),
        ]);
    }

    protected function getLexer(array &$options)
    {
        return new FormatLexer\MbString();
    }

    protected function getFormatter(array &$options)
    {
        return new AgnosticFormatter(
            $this->get('lexer', $options),
            $this->get('converter', $options),
            $this->get('symbol_formatter', $options)
        );
    }

    protected function getMapper(array &$options)
    {
        return new ResultMapper\Chain([
            new ResultMapper\StandardDateFactory(),
            new ResultMapper\StandardDate(),
            new ResultMapper\StandardDateFragmented(),
            new ResultMapper\StandardDateSolar(),
            new ResultMapper\StandardDateTime(),
        ]);
    }

    protected function getAdditionalSymbolParser(array &$options)
    {
        $parser = $this->getOptionValueChoice(
            $options,
            'additional_symbol_parser',
            $this->additional_symbol_parser,
            'none'
        );

        if (is_object($parser)) {
            return $parser;
        }

        if ($parser === false) {
            return null;
        }

        return new $parser($this->get('number_converter', $options));
    }

    protected function getSymbolParser(array &$options)
    {
        $chain = [
            new SymbolParser\PregNativeDate(),
            new SymbolParser\PregNativeRecursive(),
            new SymbolParser\PregNativeDateSolar($this->get('number_converter', $options)),
            new SymbolParser\PregNativeDateFragmented($this->get('locale', $options)),
            new SymbolParser\PregNativeDateTime(),
        ];

        if ($additional = $this->get('additional_symbol_parser', $options)) {
            array_unshift($chain, $additional);
        }

        return new SymbolParser\Chain($chain);
    }

    protected function getFormatParser(array &$options)
    {
        return new FormatParser\PregExtendedNative(
            $this->get('lexer', $options),
            $this->get('symbol_parser', $options)
        );
    }

    protected function getParser(array &$options)
    {
        return new AgnosticParser(
            $this->get('format_parser', $options),
            $this->get('mapper', $options),
            $this->get('converter', $options)
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
