<?php

namespace Popy\Calendar\Formater\Localisation;

use Popy\Calendar\Formater\LocalisationInterface;

/**
 * Hardcoded native english names, to mimic the DateTimeInterface::format
 * behaviour.
 */
class NativeHardcoded implements LocalisationInterface
{
    /**
     * Monthes names.
     *
     * @var array<string>
     */
    protected static $monthes = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December',
    ];

    /**
     * Week days names.
     *
     * @var array<string>
     */
    protected static $days = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday',
    ];

    /**
     * Ordinal labels.
     *
     * @var array<string>
     */
    protected static $ordinal = [
        'st',
        'nd',
        'rd',
        'th',
    ];

    /**
     * @inheritDoc
     */
    public function getMonthName($month)
    {
        if (isset(static::$monthes[$month - 1])) {
            return static::$monthes[$month - 1];
        }
    }

    /**
     * @inheritDoc
     */
    public function getMonthShortName($month)
    {
        if (isset(static::$monthes[$month - 1])) {
            return substr(static::$monthes[$month - 1], 0, 3);
        }
    }

    /**
     * @inheritDoc
     */
    public function getDayName($day)
    {
        if (isset(static::$days[$day - 1])) {
            return static::$days[$day - 1];
        }
    }


    /**
     * @inheritDoc
     */
    public function getDayShortName($day)
    {
        if (isset(static::$days[$day - 1])) {
            return substr(static::$days[$day - 1], 0, 3);
        }
    }

    /**
     * @inheritDoc
     */
    public function getNumberOrdinalSuffix($number)
    {
        if (isset(static::$ordinal[$number])) {
            return static::$ordinal[$number];
        }

        return end(static::$ordinal);
    }
}
