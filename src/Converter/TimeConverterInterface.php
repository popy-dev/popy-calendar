<?php

namespace Popy\Calendar\Converter;

/**
 * Time converter interface.
 */
interface TimeConverterInterface
{
    /**
     * Converts a microsecond count into the implemented time format, as array.
     *
     * @param integer $input
     *
     * @return Time
     */
    public function fromMicroSeconds($input);

    /**
     * Converts a time (of implemented format) into a microsecond count.
     *
     * @param Time $input
     *
     * @return integer
     */
    public function toMicroSeconds(Time $input);
}
