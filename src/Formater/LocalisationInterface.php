<?php

namespace Popy\Calendar\Formater;

interface LocalisationInterface
{
    /**
     * Get month name.
     *
     * @param mixed $month Month identifier (starting to 1).
     *
     * @return string|null
     */
    public function getMonthName($month);

    /**
     * Get month short name.
     *
     * @param mixed $month Month identifier (starting to 1).
     *
     * @return string|null
     */
    public function getMonthShortName($month);

    /**
     * Get day name.
     *
     * @param mixed $day Day identifier (starting to 1).
     *
     * @return string|null
     */
    public function getDayName($day);

    /**
     * Get day short name.
     *
     * @param mixed $day Day identifier (starting to 1).
     *
     * @return string|null
     */
    public function getDayShortName($day);

    /**
     * Get number ordinal suffix.
     *
     * @param integer $number
     *
     * @return string
     */
    public function getNumberOrdinalSuffix($number);
}
