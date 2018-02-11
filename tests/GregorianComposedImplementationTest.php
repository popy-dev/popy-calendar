<?php

namespace Popy\Calendar\Tests;

use DateTimeImmutable;
use PHPUnit_Framework_TestCase;
use Popy\Calendar\Factory\ConfigurableFactory;

class GregorianComposedImplementationTest extends PHPUnit_Framework_TestCase
{

    protected $calendar;

    public function setUp()
    {
        $factory = new ConfigurableFactory();
        $this->calendar = $factory->build();
    }

    public function provideFormattingTests()
    {
        // Testing timestamp 0
        yield ['1970-01-01 00:00:00 UTC', 'U', '0'];

        // Basic time offset
        yield ['1970-01-01 00:00:00 +01:00', 'U', '-3600'];
        yield ['1970-01-01 00:00:00 -01:00', 'U', '3600'];

        // Basic date fields
        yield ['2015-06-02 00:00:00 UTC', 'Y-m-d', '2015-06-02'];
        yield ['2015-06-02 00:00:00 UTC', 'y-n-j', '15-6-2'];

        // ISO 8601 tests
        yield ['2003-01-01 00:00:00 UTC', 'o W N/l H:i:s', '2003 01 3/Wednesday 00:00:00'];
        yield ['2005-01-01 00:00:00 UTC', 'o W N/l H:i:s', '2004 53 6/Saturday 00:00:00'];
    }

    /**
     * @dataProvider provideFormattingTests
     */
    public function testFormat($date, $format, $formatted)
    {
        $date = new DateTimeImmutable($date);

        $res = $this->calendar->format($date, $format);

        $this->assertSame($res, $formatted);
    }
}