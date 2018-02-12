<?php

use PHPUnit\Framework\TestCase;

use Popy\Calendar\Calendar\GregorianCalendar;

use Popy\Calendar\PresetFormater;

use Popy\Calendar\PresetParser;

class TestReadme extends TestCase
{
    public function setup() {
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
      $formater = new PresetFormater(
          $this->calendar,
          'Y-m-d'
      );
      $this->assertEquals(
          $formater->format(new DateTime()),
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
