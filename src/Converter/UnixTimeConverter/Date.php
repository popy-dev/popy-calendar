<?php

namespace Popy\Calendar\Converter\UnixTimeConverter;

use Popy\Calendar\Converter\Conversion;
use Popy\Calendar\Converter\UnixTimeConverterInterface;
use Popy\Calendar\ValueObject\DateRepresentation\Standard;

class Date implements UnixTimeConverterInterface
{
    /**
     * @inheritDoc
     */
    public function fromUnixTime(Conversion $conversion)
    {
        if (null === $res = $conversion->getTo()) {
            return;
        }

        $from = $conversion->getFrom();

        $res = $res
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

        if (null === $input->getUnixMicrotime()) {
            $input = $input->withUnixMicroTime($conversion->getUnixMicrotime());
        }

        $conversion->setTo($input);
    }
}
