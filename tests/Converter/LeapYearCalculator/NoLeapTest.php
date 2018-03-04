<?php

namespace Popy\Calendar\Tests\Converter\LeapYearCalculator;

use PHPUnit_Framework_TestCase;
use Popy\Calendar\Converter\LeapYearCalculator\NoLeap;

class NoLeapTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->calculator = new NoLeap();
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
        for ($i=0; $i < 50; $i++) { 
            yield [rand(-2000, 2000), false];
        }
    }
}
