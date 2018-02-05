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
     * Microseconds in a day
     */
    const MICROSECONDS_IN_DAY = 24*3600*1000000;

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
        $res = new Time($this->converter->getTimeFromLowerUnityCount(
            $input,
            static::$ranges
        ));

        return $res
            ->withRatio(1000000 * $input / static::MICROSECONDS_IN_DAY)
        ;
    }

    /**
     * @inheritDoc
     */
    public function toMicroSeconds(Time $input)
    {
        if (
            empty($input->getAllMeaningfull())
            && null !== $ratio = $input->getRatio()
        ) {
            return $ratio * static::MICROSECONDS_IN_DAY;
        }

        return $this->converter->getLowerUnityCountFromTime(
            $input->all(),
            static::$ranges
        );
    }
}
