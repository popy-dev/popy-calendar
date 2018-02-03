<?php

namespace Popy\Calendar\Converter\TimeConverter;

use Popy\Calendar\Converter\Utility\TimeConverter;
use Popy\Calendar\Converter\TimeConverterInterface;

/**
 * Egyptian/Republican decimal time converter.
 */
class DecimalTime implements TimeConverterInterface
{
    /**
     * Microseconds in a day
     */
    const MICROSECONDS_IN_DAY = 24*3600*1000000;

    /**
     * Decimal format ranges.
     *
     * @var array<int>
     */
    public static $ranges = [10, 100, 100, 1000000];

    /**
     * Time conversion utility.
     *
     * @var TimeConverter
     */
    protected $converter;

    /**
     * Class constructor.
     *
     * @param TimeConverter|null $converter Time conversion utility.
     */
    public function __construct(TimeConverter $converter = null)
    {
        $this->converter = $converter ?: new TimeConverter();
    }

    /**
     * Converts a microsecond count into the implemented time format, as array.
     *
     * @param integer $input
     *
     * @return array<int> [hours, minutes, seconds, microseconds, ...]
     */
    public function fromMicroSeconds($input)
    {
        $input = intval(
            ($input * array_product(static::$ranges))
            / static::MICROSECONDS_IN_DAY
        );

        return $this->converter->getTimeFromLowerUnityCount($input, static::$ranges);
    }

    /**
     * Converts a time (of implemented format) into a microsecond count.
     *
     * @param array<int> $input
     *
     * @return integer
     */
    public function toMicroSeconds(array $input)
    {
        $res = $this->converter->getLowerUnityCountFromTime($input, static::$ranges);

        return intval(
            ($res * static::MICROSECONDS_IN_DAY)
            / array_product(static::$ranges)
        );
    }
}
