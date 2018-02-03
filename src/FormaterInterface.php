<?php

namespace Popy\Calendar;

use DateTimeInterface;
use Popy\Calendar\FormaterInterface;

/**
 * A Formater is responsible of formatting an input DateTimeInterface in a string
 * representation, based on an input format.
 *
 * The format structure SHOULD be as similar as possible as the date() function format.
 *
 * The Formater MAY support some name localization.
 */
interface FormaterInterface
{
    /**
     * Format a date into a string.
     *
     * @param DateTimeInterface $input  Input date.
     * @param string            $format Date format
     *
     * @return string
     */
    public function format(DateTimeInterface $input, $format);
}
