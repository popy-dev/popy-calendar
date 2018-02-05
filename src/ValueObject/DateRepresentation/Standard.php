<?php

namespace Popy\Calendar\ValueObject\DateRepresentation;

use Popy\Calendar\ValueObject\DateTimeRepresentationInterface;
use Popy\Calendar\ValueObject\DateSolarRepresentationInterface;
use Popy\Calendar\ValueObject\DateFragmentedRepresentationInterface;

/**
 * "Standard" time representation (like Gregorian calendar)
 */
class Standard extends SolarTime implements DateTimeRepresentationInterface, DateSolarRepresentationInterface, DateFragmentedRepresentationInterface
{
    use DateFragmentedTrait;
}
