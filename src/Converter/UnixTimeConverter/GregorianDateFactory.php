<?php

namespace Popy\Calendar\Converter\UnixTimeConverter;

use Popy\Calendar\Converter\Conversion;
use Popy\Calendar\Converter\UnixTimeConverterInterface;
use Popy\Calendar\ValueObject\DateRepresentation\Standard;

/**
 * Instanciates a Gregorian date representation to initialize a Conversion->to
 * property. Has to be (one of) the first element in a chain.
 */
class GregorianDateFactory implements UnixTimeConverterInterface
{
    /**
     * @inheritDoc
     */
    public function fromUnixTime(Conversion $conversion)
    {
        $res = new Standard();
        $res = $res
            ->withUnixTime($conversion->getFrom()->getUnixTime())
            ->withUnixMicroTime($conversion->getFrom()->getUnixMicroTime())
        ;
        $conversion->setTo($res);
    }

    /**
     * @inheritDoc
     */
    public function toUnixTime(Conversion $conversion)
    {
    }
}
