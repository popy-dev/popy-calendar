<?php

namespace Popy\Calendar\Converter;

/**
 * DateParts converter interface.
 */
interface DatePartsConverterInterface
{
    /**
     * Converts day index into a DateParts
     *
     * @param DateFragmentedRepresentationInterface $input
     * @param integer                               $dayIndex
     *
     * @return DateParts
     */
    public function fromDayIndex(DateFragmentedRepresentationInterface $input, $dayIndex);

    /**
     * Converts a DateParts into a day index.
     *
     * @param DateFragmentedRepresentationInterface $input
     * @param DateParts                             $parts
     *
     * @return integer|null
     */
    public function toDayIndex(DateFragmentedRepresentationInterface $input, DateParts $parts);
}
