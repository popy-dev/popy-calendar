<?php

namespace Popy\Calendar\Converter\TimeConverter;

use Popy\Calendar\ValueObject\Time;
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
    public static $ranges = [10, 100, 100, 1000, 1000];

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
     * @inheritDoc
     */
    public function fromMicroSeconds($input)
    {
        $input = intval(
            ($input * array_product(static::$ranges))
            / static::MICROSECONDS_IN_DAY
        );

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

        $res = $this->converter->getLowerUnityCountFromTime($input->all(), static::$ranges);

        return intval(
            ($res * static::MICROSECONDS_IN_DAY / 1000000)
            / array_product(static::$ranges)
        );
    }
}
