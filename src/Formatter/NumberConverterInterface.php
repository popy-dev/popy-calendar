<?php

namespace Popy\Calendar\Formatter;

/**
 * Number converters : convert an integer to/from another number format/system.
 */
interface NumberConverterInterface
{
    /**
     * Converts an integer to implemented format/system.
     *
     * @param integer $input
     *
     * @return string
     */
    public function to($input);

    /**
     * Converts back a number from implemented format/system.
     *
     * @param string $input
     *
     * @return integer
     */
    public function from($input);
}
