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
     * @inheritDoc
     */
    public function fromMicroSeconds($input)
    {
        return new Time($this->converter->getTimeFromLowerUnityCount(
            $input,
            static::$ranges
        ));
    }

    /**
     * @inheritDoc
     */
    public function toMicroSeconds(Time $input)
    {
        return $this->converter->getLowerUnityCountFromTime(
            $input->all(),
            static::$ranges
        );
    }
}
