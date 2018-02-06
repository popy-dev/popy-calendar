<?php

namespace Popy\Calendar\Converter;

interface UnixTimeConverterInterface
{
    /**
     * Consume unix time & microtime from the conversion object and complete
     * the "Conversion->to" date representation.
     *
     * @param Conversion $conversion
     */
    public function fromUnixTime(Conversion $conversion);

    /**
     * Reads informations from Conversion->from and/or Conversion->to to either
     * complete Conversion->to (which starts as the same value than ->from)
     * and/or update Conversion->unixTime & Conversion->unixMicrotime
     *
     * @param Conversion $conversion
     */
    public function toUnixTime(Conversion $conversion);
}
