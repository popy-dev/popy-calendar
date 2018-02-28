PHP Calendar Library : Factory
==============================

To simplify building calendars, a configurable factory is available :
```Popy\Calendar\Factory\ConfigurableFactory```, and accept some options altering the building
process. This factory also allows to build smaller components, such as a Formatter, a Parser,
a Converter (and maybe more to come).

```php
<?php

use Popy\Calendar\Factory\ConfigurableFactory;

$factory = new ConfigurableFactory();

$calendar = $factory->build([
    /// options here
    'leap' => ...,
]);

$formatter = $factory->buildFormatter([
    /// options here
    'leap' => ...,
]);

$parser = $factory->buildParser([
    /// options here
    'leap' => ...,
]);

$converter = $factory->buildConverter([
    /// options here
    'leap' => ...,
]);

?>
```

Leap year calculators
---------------------

The Leap year calculators are used by converters to determine if a year is a leap one (obviously), the
year length, and some other indexes. By default, the gregorian leap calculation is used, but many other
are available, through the option 'leap' :

- noleap / none : no leap, as before the julian calendar
- julian / caesar : leap every 4 years
- modern / gregorian : leap every 4 years, but not every 100 years, but every 400 years
- futuristic : 1/4 - 1/100 + 1/400 - 1/2000 + 1/4000 - 1/20000
- persian / hijri : the persian cycle system
- von_madler : 1/4 - 1/128
- float : based on the float part of the year length

You can also provide any instance implementing the interface.
All these classes also use the options 'year_length' (year duration in days, used as integer, exepct
in the case of the float calculator)  and 'era_start_year' (reference year from where to calculate
dayIndex)

Monthes
-------

Weeks
-----