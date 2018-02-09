<?php

namespace Popy\Calendar;

use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * Handles date "conversions" between a calendar (origin) to another (target).
 * Usually, (origin) is anything as long there's a timestamp, but a converter
 * COULD handle other calendars/fields.
 */
interface ConverterInterface
{
    /**
     * Converts a date from the origin calendar to the target calendar.
     *
     * @param DateRepresentationInterface $input Input date.
     *
     * @return DateRepresentationInterface
     */
    public function to(DateRepresentationInterface $input);

    /**
     * Converts a date from the target calendar to the origin calendar.
     *
     * @param DateRepresentationInterface $input Input date.
     *
     * @return DateRepresentationInterface
     */
    public function from(DateRepresentationInterface $input);
}
