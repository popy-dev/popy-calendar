Mars time
=========

Using this library, it is possible to build any planetary-star related calendar, which will work
out-of box at the exception of the native locale which does not have enougth month names (but it's
still possible to provide any locale you want).

For instance, mars :

```php
<?php

use Popy\Calendar\Factory\ConfigurableFactory;

$factory = new ConfigurableFactory();

$mars = $factory->build([
    /**
     * Dates will be calculated relative to 1873-12-29 00:00:00 UTC just like
     * the Mars Sol Date (MSD), which is the birth date of astronomer Carl Otto
     * Lampland)
     */
    'era_start' => -3029702400,
    'era_start_year' => 1,

    /**
     * Planetary "day" length in SI seconds.
     */
    'day_length' => 88775.244,

    /**
     * Planetary year length, in planetary days.
     */
    'year_length' => 668.5991,

    /**
     * This leap calculator sill use the decimal part of the year_length to
     * determine leap years.
     */
    'leap' => 'float',

    /**
     * We could design an arbitrary calendar having month length ranging
     * randomly from 31 to 28 days. But, why ?
     *
     * This configuration will give 22 monthes of 30 days, with a complementary
     * month of 8/9 days.
     *
     * Other systems, like the one from Robert G. Aitken, would require a
     * specific (yet simple) implementation of the month calculation.
     */
    'month' => 'equal_length',
    'month_length' => 30,

    /**
     * Iso8601 week system, where year 1 starts a tuesday, because everybody
     * hates mondays.
     */
    'week' => 'iso',
    'era_start_day_index' => 1,

    /**
     * Or just use a simple AND DECIMAL week system. Why not with day names from
     * the French revolutionary calendar ? Or some great astronomers derived
     * names ?
     */
    'week' => 'simple',
    'week_length' => 10,
]);

/**
 * Will output 0001-01-01 23:21:28
 */
echo $mars->format(new DateTime('1873-12-30 00:00:00'), "Y-m-d H:i:s\n");

/**
 * Will output the current date.
 */
echo $mars->format(new DateTime(), "Y-m-d H:i:s\n");

?>
```
