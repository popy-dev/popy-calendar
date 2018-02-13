<?php

use PHPUnit\Framework\TestCase;

use Popy\Calendar\Factory\ConfigurableFactory;

class DocY10KTest extends TestCase
{
    protected $factory;
    protected $calendar;

    public function setUp()
    {
        $this->factory = new ConfigurableFactory();
        $this->calendar = $this->factory->build([
            'number' => 'rfc2550',
            'additional_symbol_parser' => 'rfc2550',
        ]);
    }

    public function testRFC2550()
    {
        // Parsing a "normal" y40k date
        $date = $this->calendar->parse('40000', 'y');

        $this->assertInstanceOf('DateTimeinterface', $date);
        $this->assertEquals($date->format('Y'), '40000');

        // Now formatting it property :
        $this->assertEquals($this->calendar->format($date, 'y-m-d'), 'A40000-01-01');

        // Parsing a RFC2550 date :
        $date = $this->calendar->parse('A10000', 'y');
        $this->assertInstanceOf('DateTimeinterface', $date);
        $this->assertEquals($date->format('Y'), '10000');
    }
}
