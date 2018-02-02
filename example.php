<?php

require './vendor/autoload.php';

use Popy\Calendar\Calendar\MankindImperialCalendar;

$imperial = new MankindImperialCalendar();

$imperialDate = '0200350.M32';
$date = $imperial->parse($imperialDate, 'format is irrelevant');
$other = $imperial->format($date->modify('+20000year'), 'format is irrelevant');

echo $imperialDate . ' -> ' . $date->format('Y-m-d H:i:s') . ' -> ' . $other .chr(10);
