<?php

namespace Popy\Calendar\ValueObject\DateRepresentation;

/**
 * Popy\Calendar\ValueObject\DateSolarRepresentationInterface implementatuon.
 */
trait DateSolarTrait
{
    /**
     * Year
     *
     * @var integer
     */
    protected $year;

    /**
     * Is leap year
     *
     * @var boolean
     */
    protected $leapYear;

    /**
     * Day Index
     *
     * @var integer
     */
    protected $dayIndex;

    /**
     * @inheritDoc
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @inheritDoc
     */
    public function isLeapYear()
    {
        return $this->leapYear;
    }

    /**
     * @inheritDoc
     */
    public function getDayIndex()
    {
        return $this->dayIndex;
    }
}
