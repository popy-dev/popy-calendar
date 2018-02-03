<?php

namespace Popy\Calendar\Converter\TimeConverter;

use Popy\Calendar\Converter\Utility\TimeConverter;
use Popy\Calendar\Converter\TimeConverterInterface;

/**
 * Duodecimal time converter.
 */
class DuoDecimalTime implements TimeConverterInterface
{
    /**
     * Time conversion utility.
     *
     * @var TimeConverter
     */
    protected $converter;

    /**
     * DuoDecimal format ranges.
     *
     * @var array<int>
     */
    public static $ranges = [24, 60, 60, 1000000];

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
        return $this->converter->getLowerUnityCountFromTime($input, static::$ranges);
    }
}
