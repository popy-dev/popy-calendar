<?php

namespace Popy\Calendar\ValueObject;

/**
 * Date representation having a "time" notion.
 */
interface DateTimeRepresentationInterface extends DateRepresentationInterface
{
    /**
     * Gets the time representation.
     *
     * @return Time
     */
    public function getTime();

    /**
     * Gets a new date instance with the input Time representation.
     *
     * @param Time $time
     *
     * @return static
     */
    public function withTime(Time $time);
}
