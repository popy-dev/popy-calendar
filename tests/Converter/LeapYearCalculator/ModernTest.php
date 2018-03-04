<?php

namespace Popy\Calendar\Tests\Converter\LeapYearCalculator;

use PHPUnit_Framework_TestCase;
use Popy\Calendar\Converter\LeapYearCalculator\Modern;

class ModernTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->calculator = new Modern();
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
            [100, false],
            [200, false],
            [204, true],
            [300, false],
            [400, true],
            [2000, true],
        ];
    }
}
