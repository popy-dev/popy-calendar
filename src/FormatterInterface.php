<?php

namespace Popy\Calendar;

use DateTimeInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * A Formatter is responsible of formatting an input Date in a string
 * representation, based on an input format.
 *
 * The Formatter MAY support some name localization.
 */
interface FormatterInterface
{
    /**
     * Format a date into a string.
     *
     * @param DateTimeInterface $input  Input date.
     * @param string            $format Date format.
     *
     * @return string
     */
    public function format(DateTimeInterface $input, $format);

    /**
     * Format a date representation into a string.
     *
     * @param DateRepresentationInterface $input  Input date.
     * @param string                      $format Date format.
     *
     * @return string
     */
    public function formatDateRepresentation(DateRepresentationInterface $input, $format);
}
