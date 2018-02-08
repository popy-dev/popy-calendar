<?php

namespace Popy\Calendar\Converter\UnixTimeConverter;

use Popy\Calendar\ValueObject\Time as TimeObject;
use Popy\Calendar\Converter\Conversion;
use Popy\Calendar\Converter\UnixTimeConverterInterface;
use Popy\Calendar\ValueObject\DateTimeRepresentationInterface;

/**
 * Hanldles DateTimeRepresentationInterface's time.
 */
class Time implements UnixTimeConverterInterface
{
    /**
     * Day length in seconds.
     *
     * @var integer
     */
    protected $dayLengthInSeconds = 24 * 3600;

    /**
     * Time format ranges.
     *
     * @var array<int>
     */
    public $ranges = [24, 60, 60, 1000, 1000];

    /**
     * Class constructor.
     *
     * @param array|null   $ranges             Time segments ranges/sizes.
     * @param integer|null $dayLengthInSeconds Day length.
     */
    public function __construct(array $ranges = null, $dayLengthInSeconds = null)
    {
        if (null !== $ranges) {
            $this->ranges = $ranges;
        }

        if (null !== $dayLengthInSeconds) {
            $this->dayLengthInSeconds = $dayLengthInSeconds;
        }
    }


    /**
     * @inheritDoc
     */
    public function fromUnixTime(Conversion $conversion)
    {
        $input = $conversion->getTo();

        if (!$input instanceof DateTimeRepresentationInterface) {
            return;
        }

        $unixTime = $conversion->getUnixTime();
        $microSec = $conversion->getUnixMicroTime()
            + ($unixTime % $this->dayLengthInSeconds) * 1000000
        ;

        $time = $this->convertMicrosecondsToTime($microSec);

        // Removing the consumed seconds
        $unixTime -= $unixTime % $this->dayLengthInSeconds;

        $conversion
            ->setUnixTime($unixTime)
            ->setUnixMicroTime(0)
            ->setTo($input->withTime($time))
        ;
    }

    /**
     * @inheritDoc
     */
    public function toUnixTime(Conversion $conversion)
    {
        $input = $conversion->getTo();

        if (!$input instanceof DateTimeRepresentationInterface) {
            return;
        }

        $microsecPerDay = $this->dayLengthInSeconds * 1000000;

        $time = $input->getTime();

        $microsec = $this->convertTimeToMicroseconds($time);

        $conversion
            ->setUnixTime($conversion->getUnixTime() + intval($microsec / 1000000))
            ->setUnixMicroTime($microsec % 1000000)
        ;
    }

    /**
     * Convert a time expressed in microseconds, into a Time object.
     *
     * @param integer $time
     *
     * @return TimeObject
     */
    public function convertMicrosecondsToTime($time)
    {
        // Calculating day-ratio;
        // Dividing microseconds per seconds results in the intended value
        $ratio = intval($time / $this->dayLengthInSeconds);

        // If using a different time format, apply ratio
        $microsecPerDay = $this->dayLengthInSeconds * 1000000;
        $timeScale = array_product($this->ranges);
        if ($microsecPerDay !== $timeScale) {
            $time = intval(($time * $timeScale) / $microsecPerDay);
        }

        $len = count($this->ranges);
        $res = array_fill(0, $len, 0);

        for ($i=$len - 1; $i > -1 ; $i--) {
            $res[$i] = $time % $this->ranges[$i];
            $time = intval($time / $this->ranges[$i]);
        }

        return new TimeObject($res, $this->ranges, $ratio);
    }

    /**
     * Converts a "Time" into its value in its lower unit.
     *
     * @param TimeObject $time
     *
     * @return integer
     */
    public function convertTimeToMicroseconds(TimeObject $time)
    {
        $microsecPerDay = $this->dayLengthInSeconds * 1000000;

        // IF time had no meaningfull informations, fallback to a day ratio.
        if (
            !$time->countMeaningfull()
            && null !== $ratio = $time->getRatio()
        ) {
            return $ratio * $this->dayLengthInSeconds;
        } 

        $len = count($this->ranges);
        $res = 0;
   
        for ($i=0; $i < $len; $i++) {
            $res = $res * $this->ranges[$i] + (int)$time->get($i);
        }

        // If using a different time format, apply ratio
        if ($microsecPerDay !== $timeScale = array_product($this->ranges)) {
            return intval(($res * $microsecPerDay) / $timeScale);
        }

        return $res;
    }
}
