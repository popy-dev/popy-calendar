<?php

namespace Popy\Calendar\Tests;

use DateTime;
use PHPUnit\Framework\TestCase;
use Popy\Calendar\PresetParser;
use Popy\Calendar\PresetFormatter;
use Popy\Calendar\Calendar\GregorianCalendar;

class ReadmeTest extends TestCase
{
    protected $calendar;

    public function setUp() {
        $this->calendar = new GregorianCalendar();
    }

    public function testUseGregorianCalendarFormat() {
        $this->assertEquals(
            $this->calendar->format(new DateTime(), 'Y-m-d'),
            date('Y-m-d')
        );
    }

    public function testUseGregorianCalendarParse() {
        $parseResult = $this->calendar->parse('2000-01-01', 'Y-m-d');

        $this->assertEquals(
            $parseResult->format('Y-m-d'),
            '2000-01-01'
        );
    }

    public function testPresetFormatter() {
        $formatter = new PresetFormatter(
            $this->calendar,
            'Y-m-d'
        );

        $this->assertEquals(
            $formatter->format(new DateTime()),
            date('Y-m-d')
        );
    }

    public function testPresetParser() {
        $parser = new PresetParser($this->calendar, 'Y-m-d');
        $parseResult = $parser->parse('2017-05-01');

        $this->assertEquals(
            $parseResult->format('Y-m-d'),
            '2017-05-01'
        );
    }
}
