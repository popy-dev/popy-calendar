<?php

namespace Popy\Calendar;

use DateTimeInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

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
     * @return DateRepresentationInterface
     */
    public function fromDateTimeInterface(DateTimeInterface $input);

    /**
     * Converts a date representation into a DateTimeInterface
     *
     * @param DateRepresentationInterface $input
     *
     * @return DateTimeInterface
     */
    public function toDateTimeInterface(DateRepresentationInterface $input);
}
