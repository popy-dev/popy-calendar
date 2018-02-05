<?php

namespace Popy\Calendar\ValueObject\DateRepresentation;

use Popy\Calendar\ValueObject\DateTimeRepresentationInterface;
use Popy\Calendar\ValueObject\SolarTimeRepresentationInterface;
use Popy\Calendar\ValueObject\DateFragmentedRepresentationInterface;

/**
 * "Standard" time representation (like Gregorian calendar)
 */
class Standard extends SolarTime implements DateTimeRepresentationInterface, SolarTimeRepresentationInterface, DateFragmentedRepresentationInterface
{
    use DateFragmentedTrait;
}
