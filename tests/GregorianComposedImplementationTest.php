<?php

namespace Popy\Calendar\Tests;

use DateTimeZone;
use DateTimeImmutable;
use PHPUnit_Framework_TestCase;
use Popy\Calendar\Factory\ConfigurableFactory;

class GregorianComposedImplementationTest extends PHPUnit_Framework_TestCase
{
    protected $calendar;
    protected $timezone;

    public function setUp()
    {
        $factory = new ConfigurableFactory();
        $this->calendar = $factory->build();

        $this->timezone = new DateTimeZone('UTC');
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
        // Default configuration should convert back this properly
        yield ['1975-06-02 00:00:00 UTC', 'y-n-j', '75-6-2'];

        yield ['2005-01-01 00:00:00 UTC', 'jS F Y', '1st January 2005'];
        yield ['2005-02-02 00:00:00 UTC', 'jS F Y', '2nd February 2005'];
        yield ['2005-03-03 00:00:00 UTC', 'jS F Y', '3rd March 2005'];
        yield ['2005-04-04 00:00:00 UTC', 'jS F Y', '4th April 2005'];

        // Leap years
        yield ['1900-02-01 00:00:00 UTC', 'L t', '0 28', true];
        yield ['2000-02-01 00:00:00 UTC', 'L t', '1 29', true];

        // ISO 8601 tests
        yield ['2003-01-01 00:00:00 UTC', 'o W N/l H:i:s', '2003 01 3/Wednesday 00:00:00'];
        yield ['2005-01-01 00:00:00 UTC', 'o W N/l H:i:s', '2004 53 6/Saturday 00:00:00'];

        // Time
        // Basic test
        yield ['2005-01-01 17:30:10 UTC', 'Y-m-d H:i:s', '2005-01-01 17:30:10'];
        // Testing 0 padding
        yield ['2005-01-01 05:30:10 UTC', 'Y-m-d H:i:s', '2005-01-01 05:30:10'];
        // Testing without 0 padding
        yield ['2005-01-01 05:30:10 UTC', 'Y-m-d G:i:s', '2005-01-01 5:30:10'];
        // Testing 12hour format with 0 padding ...
        yield ['2005-01-01 17:30:10 UTC', 'Y-m-d ha:i:s', '2005-01-01 05pm:30:10'];
        /// ... and without
        yield ['2005-01-01 17:30:10 UTC', 'Y-m-d ga:i:s', '2005-01-01 5pm:30:10'];
        // Testing midnight special case
        yield ['2005-01-01 00:00:00 UTC', 'Y-m-d ha:i:s', '2005-01-01 12am:00:00'];
        yield ['2005-01-01 00:00:00 UTC', 'Y-m-d ga:i:s', '2005-01-01 12am:00:00'];
        // Testing noon special case
        yield ['2005-01-01 12:00:00 UTC', 'Y-m-d ha:i:s', '2005-01-01 12pm:00:00'];
        yield ['2005-01-01 12:00:00 UTC', 'Y-m-d ga:i:s', '2005-01-01 12pm:00:00'];
        // testing uppercase AM/PM
        yield ['2005-01-01 17:30:10 UTC', 'Y-m-d gA:i:s', '2005-01-01 5PM:30:10'];
        yield ['2005-01-01 05:30:10 UTC', 'Y-m-d hA:i:s', '2005-01-01 05AM:30:10'];
        // Testing swatch time
        yield ['2005-01-01 0:00:00 +01:00', 'Y-m-d B', '2005-01-01 000'];
        yield ['2005-01-01 0:01:27 +01:00', 'Y-m-d B', '2005-01-01 001'];
        yield ['2005-01-01 12:00:00 +01:00', 'Y-m-d B', '2005-01-01 500'];
        // testing with different time zone
        yield ['2005-01-01 11:57:36 UTC', 'Y-m-d B', '2005-01-01 540'];
        yield ['2005-01-01 00:00:00 -11:00', 'Y-m-d B', '2005-01-01 500'];

        // testing recursive formats
        yield ['2005-01-01 11:57:36 +01:00', 'c', '2005-01-01T11:57:36+01:00'];
        yield ['2005-01-01 11:57:36 +01:00', 'r', 'Sat, 01 Jan 2005 11:57:36 +0100'];
        // not alone
        yield ['2005-01-01 11:57:36 +01:00', '\\YY c \\YY', 'Y2005 2005-01-01T11:57:36+01:00 Y2005'];
        yield ['2005-01-01 11:57:36 +01:00', '\\YY r \\YY', 'Y2005 Sat, 01 Jan 2005 11:57:36 +0100 Y2005'];


        // Testing timestamp 0
        yield ['1970-01-01 00:00:00 Europe/Berlin', 'U e', '-3600 Europe/Berlin'];
        yield ['1970-01-01 00:00:00 Europe/Berlin', 'U T', '-3600 CET'];

    }

    /**
     * @dataProvider provideFormattingTests
     */
    public function testFormat($date, $format, $formatted)
    {
        $date = new DateTimeImmutable($date);

        $res = $this->calendar->format($date, $format);

        $this->assertSame($formatted, $res);
    }

    /**
     * @dataProvider provideFormattingTests
     */
    public function testParse($date, $format, $formatted, $skip = false)
    {
        if ($skip) {
            return;
        }

        $date = new DateTimeImmutable($date);
        $res = $this->calendar->parse($formatted, $format, $this->timezone);

        if ($res) {
            $res = $res->getTimestamp();
        }

        // COmpare timestamps, as timezone can be lost.
        $this->assertSame($date->getTimestamp(), $res);
    }
}