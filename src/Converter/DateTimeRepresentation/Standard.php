<?php

namespace Popy\Calendar\Converter\DateTimeRepresentation;

use Popy\Calendar\Converter\DateTimeRepresentationInterface;
use Popy\Calendar\Converter\SolarTimeRepresentationInterface;
use Popy\Calendar\Converter\DateFragmentedRepresentationInterface;

/**
 * "Standard" time representation (like Gregorian calendar)
 */
class Standard extends SolarTime implements DateTimeRepresentationInterface, SolarTimeRepresentationInterface, DateFragmentedRepresentationInterface
{
    use DateFragmentedTrait;
}
