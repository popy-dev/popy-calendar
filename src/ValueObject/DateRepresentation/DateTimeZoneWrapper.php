<?php

namespace Popy\Calendar\ValueObject\DateRepresentation;

use DateTimeZone;

class DateTimeZoneWrapper extends AbstractDate
{
    /**
     * Class constructor.
     *
     * @param DateTimeZone $timezone
     */
    public function __construct(DateTimeZone $timezone = null)
    {
        $this->timezone = $timezone = null;
    }
}
