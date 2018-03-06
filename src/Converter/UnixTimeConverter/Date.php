<?php

namespace Popy\Calendar\Converter\UnixTimeConverter;

use Popy\Calendar\Converter\Conversion;
use Popy\Calendar\Converter\UnixTimeConverterInterface;
use Popy\Calendar\ValueObject\DateRepresentation\Standard;

/**
 * Day in the year representation
 *
 * Fragment units are, usually, named as, and used as :
 * 0 => months (format symbols m, etc ...)
 *
 * Transversal units are, usually, named as and used as :
 * 0 => ISO 8601 year (format symbol o).
 * 1 => ISO 8601 week number (format symbol W)
 * 2 => ISO 8601 day index (format symbol N)
 */
class Date implements UnixTimeConverterInterface
{
    /**
     * @inheritDoc
     */
    public function fromUnixTime(Conversion $conversion)
    {
        $from = $conversion->getFrom();

        $res = $conversion->getTo()
            ->withUnixTime($from->getUnixTime())
            ->withUnixMicroTime($from->getUnixMicroTime())
            ->withTimezone($from->getTimezone())
        ;

        $conversion->setTo($res);
    }

    /**
     * @inheritDoc
     */
    public function toUnixTime(Conversion $conversion)
    {
        $input = $conversion->getTo();

        if (null === $input->getUnixTime()) {
            $input = $input->withUnixTime($conversion->getUnixTime());
        }

        if (null === $input->getUnixMicroTime()) {
            $input = $input->withUnixMicroTime($conversion->getUnixMicroTime());
        }

        $conversion->setTo($input);
    }
}
