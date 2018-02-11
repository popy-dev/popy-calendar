<?php

require './vendor/autoload.php';

use Popy\Calendar\Factory\ConfigurableFactory;
use Popy\Calendar\Calendar\MankindImperialCalendar;

$factory = new ConfigurableFactory();

$calendar = $factory->build();


$source = new DateTime('2005-01-01 17:00:00');
$sourceStr = $source->format($f = 'o W w/l ha');

// Comparing date parsing from ISO weeks & years
$native = DateTime::createFromFormat($f, $sourceStr);
$mine = $calendar->parse($sourceStr, $f);

echo 'Source : ' . $source->format($ff = 'Y-m-d H:i:s') . ' formated as "' . $sourceStr . '"' . chr(10);
echo 'Native : ' . ($native ? $native->format($ff) : 'false') . chr(10);
echo 'Parser : ' . ($mine ? $mine->format($ff) : 'false') . chr(10);



$imperial = new MankindImperialCalendar();

$imperialDate = '0200350.M32';
$date = $imperial->parse($imperialDate, 'format is irrelevant');
$other = $imperial->format($date->modify('+20000year'), 'format is irrelevant');

echo $imperialDate . ' -> ' . $date->format('Y-m-d H:i:s') . ' -> ' . $other .chr(10);
