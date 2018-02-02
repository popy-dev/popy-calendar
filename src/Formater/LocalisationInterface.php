<?php

namespace Popy\Calendar\Formater;

interface LocalisationInterface
{
    /**
     * Get month name.
     * 
     * @param integer $month Month number.
     *
     * @return string|null
     */
    public function getMonthName($month);

    /**
     * Get day week name.
     * 
     * @param integer $day Day week index.
     *
     * @return string|null
     */
    public function getWeekDayName($day);

    /**
     * Get day name.
     * 
     * @param integer $day Day index (in year).
     * 
     * @return string|null
     */
    public function getDayName($day);

    /**
     * Get number ordinal suffix.
     *
     * @param integer $number
     *
     * @return string|null
     */
    public function getNumberOrdinalSuffix($number);
}