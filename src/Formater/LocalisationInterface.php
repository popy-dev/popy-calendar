<?php

namespace Popy\Calendar\Formater;

interface LocalisationInterface
{
    /**
     * Get month name.
     * 
     * @param mixed $month Month identifier.
     *
     * @return string|null
     */
    public function getMonthName($month);

    /**
     * Get month short name.
     * 
     * @param mixed $month Month identifier.
     *
     * @return string|null
     */
    public function getMonthShortName($month);

    /**
     * Get day name.
     * 
     * @param mixed $day Day identifier.
     * 
     * @return string|null
     */
    public function getDayName($day);

    /**
     * Get day short name.
     * 
     * @param mixed $day Day identifier.
     * 
     * @return string|null
     */
    public function getDayShortName($day);

    /**
     * Get number ordinal suffix.
     *
     * @param integer $number
     *
     * @return string|null
     */
    public function getNumberOrdinalSuffix($number);
}