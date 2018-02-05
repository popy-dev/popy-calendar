<?php

namespace Popy\Calendar\ValueObject\DateRepresentation;

use Popy\Calendar\ValueObject\DateTimeRepresentationInterface;
use Popy\Calendar\ValueObject\DateSolarRepresentationInterface;

/**
 * Minimal implementatuon.
 */
class SolarTime extends AbstractDate implements DateTimeRepresentationInterface, DateSolarRepresentationInterface
{
    use DateTimeTrait;
    use DateSolarTrait;
    
    /**
     * Class constructor.
     *
     * @param integer $year
     * @param boolean $leapYear
     * @param integer $dayIndex
     */
    public function __construct($year, $leapYear, $dayIndex)
    {
        $this->year     = $year;
        $this->leapYear = $leapYear;
        $this->dayIndex = $dayIndex;
    }
}