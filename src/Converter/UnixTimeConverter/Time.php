<?php

namespace Popy\Calendar\Converter\UnixTimeConverter;

use Popy\Calendar\Converter\Conversion;
use Popy\Calendar\Converter\TimeConverterInterface;
use Popy\Calendar\Converter\UnixTimeConverterInterface;
use Popy\Calendar\ValueObject\DateTimeRepresentationInterface;

/**
 * Hanldles DateTimeRepresentationInterface's time.
 */
class Time implements UnixTimeConverterInterface
{
    /**
     * Time converter.
     *
     * @var TimeConverterInterface
     */
    protected $timeConverter;

    /**
     * Day length in seconds.
     *
     * @var integer
     */
    protected $dayLengthInSeconds = 24 * 3600;

    /**
     * Class constructor.
     *
     * @param TimeConverterInterface $timeConverter Time converter.
     */
    public function __construct(TimeConverterInterface $timeConverter)
    {
        $this->timeConverter = $timeConverter;
    }

    /**
     * @inheritDoc
     */
    public function fromUnixTime(Conversion $conversion)
    {
        if (!$conversion->getTo() instanceof DateTimeRepresentationInterface) {
            return;
        }

        $res = $conversion->getTo();

        $unixTime = $conversion->getUnixTime();

        $remainingMicroSeconds = $conversion->getUnixMicroTime()
            + ($unixTime % $this->dayLengthInSeconds) * 1000000
        ;

        $res = $res->withTime(
            $this->timeConverter->fromMicroSeconds($remainingMicroSeconds)
        );

        $unixTime -= $unixTime % $this->dayLengthInSeconds;

        $conversion
            ->setUnixTime($unixTime)
            ->setUnixMicroTime(0)
            ->setTo($res)
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

        $microsec = $this->timeConverter->toMicroSeconds($input->getTime());

        $conversion
            ->setUnixTime($conversion->getUnixTime() + intval($microsec / 1000000))
            ->setUnixMicroTime($microsec % 1000000)
        ;
    }
}
