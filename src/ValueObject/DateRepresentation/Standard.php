<?php

namespace Popy\Calendar\ValueObject\DateRepresentation;

use Popy\Calendar\ValueObject\DateTimeRepresentationInterface;
use Popy\Calendar\ValueObject\DateSolarRepresentationInterface;
use Popy\Calendar\ValueObject\DateFragmentedRepresentationInterface;

/**
 * "Standard" time representation (like Gregorian calendar) :
 * - is a solar date
 * - has date parts (monthes) and transversals (weekyear, weekindex, day of week)
 * - as time (hour, minute, seconds, milliseconds, microseconds)
 */
class Standard extends Date implements DateTimeRepresentationInterface, DateSolarRepresentationInterface, DateFragmentedRepresentationInterface
{
    use DateTimeTrait;
    use DateSolarTrait;
    use DateFragmentedTrait;
}
