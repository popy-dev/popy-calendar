<?php

namespace Popy\Calendar\ValueObject;

/**
 * Date representation having a "fragmented" structure.
 */
interface DateFragmentedRepresentationInterface extends DateRepresentationInterface
{
    /**
     * Gets the date parts representation.
     *
     * @return DateParts
     */
    public function getDateParts();

    /**
     * Gets a new date instance with the input DateParts.
     *
     * @param DateParts $dateParts
     *
     * @return static
     */
    public function withDateParts(DateParts $dateParts);
}
