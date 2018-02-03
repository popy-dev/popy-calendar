<?php

namespace Popy\Calendar\Converter\DateTimeRepresentation;

use Popy\Calendar\Converter\SolarTimeRepresentationInterface;

/**
 * Minimal abstract implementatuon.
 */
abstract class AbstractSolarTime extends AbstractDateTime implements SolarTimeRepresentationInterface
{
    /**
     * Year
     *
     * @var integer
     */
    protected $year;
    
    /**
     * Day Index
     *
     * @var integer
     */
    protected $dayIndex;

    /**
     * Time informations.
     *
     * @var array<int>
     */
    protected $time;

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
    public function getDayIndex()
    {
        return $this->dayIndex;
    }

    /**
     * @inheritDoc
     */
    public function getTime()
    {
        return $this->time;
    }
}
