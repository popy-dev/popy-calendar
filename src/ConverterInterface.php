<?php

namespace Popy\Calendar;

use DateTimeInterface;
use Popy\Calendar\Converter\DateTimeRepresentationInterface;

/**
 * Handles date "conversions" between a DateTimeInterface into any other
 * Date representation.
 */
interface ConverterInterface
{
    /**
     * Converts a DateTimeInterface to another representation.
     *
     * @param DateTime $input
     *
     * @return DateTimeRepresentationInterface
     */
    public function fromDateTimeInterface(DateTimeInterface $input);

    /**
     * Converts a date representation into a DateTimeInterface
     *
     * @param DateTimeRepresentationInterface $input
     *
     * @return DateTimeInterface
     */
    public function toDateTimeInterface(DateTimeRepresentationInterface $input);
}
