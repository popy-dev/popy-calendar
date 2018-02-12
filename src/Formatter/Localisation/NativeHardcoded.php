<?php

namespace Popy\Calendar\Formatter\Localisation;

use Popy\Calendar\Formatter\LocalisationInterface;

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
        if (isset(static::$monthes[$month])) {
            return static::$monthes[$month];
        }
    }

    /**
     * @inheritDoc
     */
    public function getMonthShortName($month)
    {
        if (isset(static::$monthes[$month])) {
            return substr(static::$monthes[$month], 0, 3);
        }
    }

    /**
     * @inheritDoc
     */
    public function getDayName($day)
    {
        if (isset(static::$days[$day])) {
            return static::$days[$day];
        }
    }


    /**
     * @inheritDoc
     */
    public function getDayShortName($day)
    {
        if (isset(static::$days[$day])) {
            return substr(static::$days[$day], 0, 3);
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
