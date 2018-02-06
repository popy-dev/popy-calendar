<?php

namespace Popy\Calendar\Converter\UnixTimeConverter;

use Popy\Calendar\Converter\Conversion;
use Popy\Calendar\Converter\UnixTimeConverterInterface;
use Popy\Calendar\Converter\DatePartsConverterInterface;
use Popy\Calendar\ValueObject\DateSolarRepresentationInterface;
use Popy\Calendar\ValueObject\DateFragmentedRepresentationInterface;

/**
 * Handles DateFragmentedRepresentationInterface date.
 */
class DateParts implements UnixTimeConverterInterface
{
    /**
     * Parts converter.
     *
     * @var DatePartsConverterInterface
     */
    protected $partsConverter;

    /**
     * Class constructor.
     *
     * @param DatePartsConverterInterface $partsConverter
     */
    public function __construct(DatePartsConverterInterface $partsConverter)
    {
        $this->partsConverter = $partsConverter;
    }

    /**
     * @inheritDoc
     */
    public function fromUnixTime(Conversion $conversion)
    {
        if (
            !$conversion->getTo() instanceof DateFragmentedRepresentationInterface
            || !$conversion->getTo() instanceof DateSolarRepresentationInterface
        ) {
            return;
        }

        $res = $conversion->getTo();

        $res = $res->withDateParts(
            $this->partsConverter->fromDayIndex($res, $res->getDayIndex())
        );

        $conversion->setTo($res);
    }

    /**
     * @inheritDoc
     */
    public function toUnixTime(Conversion $conversion)
    {
        $input = $conversion->getTo() ?: $conversion->getFrom();

        if (
            !$input instanceof DateFragmentedRepresentationInterface
            || !$input instanceof DateSolarRepresentationInterface
        ) {
            return;
        }

        if (null !== $input->getDayIndex()) {
            return ;
        }

        $res = $conversion->getTo();

        $res = $res->withDayIndex(
            $this->partsConverter->toDayIndex($res, $res->getDateParts()),
            $res->getEraDayIndex()
        );

        $conversion->setTo($res);

    }
}
