<?php

namespace Popy\Calendar\Converter\UnixTimeConverter;

use Popy\Calendar\Converter\Conversion;
use Popy\Calendar\Converter\UnixTimeConverterInterface;
use Popy\Calendar\ValueObject\DateRepresentation\Standard;

/**
 * Instanciates a Gregorian date representation to initialize a Conversion->to
 * property. Has to be (one of) the first element in a chain.
 */
class StandardDateFactory implements UnixTimeConverterInterface
{
    /**
     * @inheritDoc
     */
    public function fromUnixTime(Conversion $conversion)
    {
        $conversion->setTo(new Standard());
    }

    /**
     * @inheritDoc
     */
    public function toUnixTime(Conversion $conversion)
    {
    }
}
