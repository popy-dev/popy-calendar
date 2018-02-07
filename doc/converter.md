PHP Calendar Library : Converters
=================================

A Converter is a class implementing the `Popy\Calendar\ConverterInterface` interface. Its purpose is
to "convert" a date into a `Popy\Calendar\ValueObject\DateRepresentationInterface`, which is a more
complete representation of a date than a timestamp for instance.

The goal of this is to be able to use different calendars than the native Gregorian calendar. Even
if you use only the Gregorian calendar which is native to PHP, you may want to use a Converter for
some reasons :

* If you want a different format syntax
* If you want to use a different locale than the native english
* If you want to implement a better format parse (Y10k compatibility ?)

AgnosticConverter
-----------------

The `Popy\Calendar\Converter\AgnosticConverter` is a composable converter implementation of the
ConverterInterface. Its only purpose is to delegate the work to sub-converters, implementing the
`Popy\Calendar\Converter\UnixTimeConverterInterface`. Some implementations of this interface are
available, handling time offsets, year calculation, leap yers, etc... allowing to compose a
fully operational converter. For instance, lets build a Gregorian converter :

```php
<?php

require './vendor/autoload.php';

use Popy\Calendar\Converter\AgnosticConverter;
use Popy\Calendar\Converter\UnixTimeConverter;
use Popy\Calendar\Converter\LeapYearCalculator;
use Popy\Calendar\Converter\DatePartsConverter;
use Popy\Calendar\Converter\TimeConverter;

$converter = new AgnosticConverter();
$converter->addConverters(
    new UnixTimeConverter\GregorianDateFactory(),
    new UnixTimeConverter\Date(),
    new UnixTimeConverter\TimeOffset(),
    new UnixTimeConverter\DateSolar(
        new LeapYearCalculator\Modern(),
        0,
        1970
    ),
    new UnixTimeConverter\DateParts(
        new DatePartsConverter\StandardMonthes()
    ),
    new UnixTimeConverter\Time(
        new TimeConverter\DuoDecimalTime()
    ),
]);
?>
```

If you wanted to have a similar calendar, but having a greater precision on leap days, you could
for instance replace the use of `Popy\Calendar\Converter\LeapYearCalculator\Modern` by
`Popy\Calendar\Converter\LeapYearCalculator\Futuristic`, or the
`Popy\Calendar\Converter\LeapYearCalculator\Persian` one.

Want a base 10 time system ? Replace `Popy\Calendar\Converter\TimeConverter\DuoDecimalTime` by
`Popy\Calendar\Converter\TimeConverter\DecimalTime`.
