<?php

namespace Popy\Calendar\Tests\Converter\LeapYearCalculator;

use PHPUnit_Framework_TestCase;
use Popy\Calendar\Converter\LeapYearCalculator\Persian;

/**
 * Persian / Solar Hijri leap year calculator test.
 *
 * @link https://en.wikipedia.org/wiki/Solar_Hijri_calendar#Solar_Hijri_algorithmic_calendar
 */
class PersianTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->calculator = new Persian();
    }

    /**
     * @dataProvider provideYearBasedMethods
     */
    public function testYearBasedMethods($year, $leap)
    {
        $this->assertSame($leap, $this->calculator->isLeapYear($year));
    }

    public function provideYearBasedMethods()
    {
        return [
            [1354, true],
            [1363, false],
        ];
    }
}
