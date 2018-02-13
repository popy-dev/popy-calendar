<?php

namespace Popy\Calendar\Formatter;

/**
 * Number converters : convert an input value to/from another number format/system.
 *
 * To allow great numbers manupulation, string values can be used in place of
 * integers.
 */
interface NumberConverterInterface
{
    /**
     * Converts an integer to implemented format/system.
     *
     * @param integer|string $input
     *
     * @return string
     */
    public function to($input);

    /**
     * Converts back a number from implemented format/system.
     *
     * @param string $input
     *
     * @return integer|string|null
     */
    public function from($input);
}
