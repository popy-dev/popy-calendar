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
     * @var integer|null
     */
    protected $year;

    /**
     * Is leap year
     *
     * @var boolean|null
     */
    protected $leapYear;

    /**
     * Day Index
     *
     * @var integer|null
     */
    protected $dayIndex;

    /**
     * Day Index
     *
     * @var integer|null
     */
    protected $eraDayIndex;

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

    /**
     * @inheritDoc
     */
    public function getEraDayIndex()
    {
        return $this->eraDayIndex;
    }

    /**
     * @inheritDoc
     */
    public function withYear($year, $isLeap)
    {
        $res = clone $this;
        $res->year = $year;
        $res->leapYear = $isLeap;

        return $res;
    }

    /**
     * @inheritDoc
     */
    public function withDayIndex($dayIndex, $eraDayIndex)
    {
        $res = clone $this;
        $res->dayIndex = $dayIndex;
        $res->eraDayIndex = $eraDayIndex;
        
        return $res;
    }
}
