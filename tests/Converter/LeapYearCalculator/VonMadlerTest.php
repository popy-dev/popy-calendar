<?php

namespace Popy\Calendar\Tests\Converter\LeapYearCalculator;

use PHPUnit_Framework_TestCase;
use Popy\Calendar\Converter\LeapYearCalculator\VonMadler;

class VonMadlerTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->calculator = new VonMadler();
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
            [1, false],
            [2, false],
            [4, true],
            [8, true],
            [100, true],
            [124, true],
            [128, false],
        ];
    }
}
