<?php

namespace Popy\Calendar\Converter\DateTimeRepresentation;

use Popy\Calendar\Converter\SolarTimeRepresentationInterface;

/**
 * Minimal implementatuon.
 */
class SolarTime extends AbstractDateTime implements SolarTimeRepresentationInterface
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
