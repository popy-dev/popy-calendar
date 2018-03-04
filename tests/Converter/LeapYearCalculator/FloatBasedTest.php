<?php

namespace Popy\Calendar\Tests\Converter\LeapYearCalculator;

use PHPUnit_Framework_TestCase;
use Popy\Calendar\Converter\LeapYearCalculator\FloatBased;

class FloatBasedTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->calculator = new FloatBased(365.25);
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
            [3, false],
            [4, true],
        ];
    }
}
