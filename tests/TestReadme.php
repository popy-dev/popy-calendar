<?php

use PHPUnit\Framework\TestCase;

use Popy\Calendar\Calendar\GregorianCalendar;

use Popy\Calendar\PresetFormater;

use Popy\Calendar\PresetParser;

class TestReadme extends TestCase
{
    public function testUseGregorianCalendarFormat() {
        $calendar = new GregorianCalendar();
        $this->assertEquals(
            $calendar->format(new DateTime(), 'Y-m-d'),
            date('Y-m-d')
        );
    }

    public function testUseGregorianCalendarParse() {
        $calendar = new GregorianCalendar();
        $parseResult = $calendar->parse('2000-01-01', 'Y-m-d');
        $this->assertEquals(
            $parseResult->format('Y-m-d'),
            '2000-01-01'
        );
    }

    public function testPresetFormatter() {
      $formater = new PresetFormater(
          new GregorianCalendar(),
          'Y-m-d'
      );
      $this->assertEquals(
          $formater->format(new DateTime()),
          date('Y-m-d')
      );
    }

    public function testPresetParser() {
        $parser = new PresetParser(new GregorianCalendar(), 'Y-m-d');
        $parseResult = $parser->parse('2017-05-01');
        $this->assertEquals(
            $parseResult->format('Y-m-d'),
            '2017-05-01'
        );
    }
}
