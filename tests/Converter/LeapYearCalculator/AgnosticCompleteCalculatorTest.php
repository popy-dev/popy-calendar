<?php

namespace Popy\Calendar\Tests\Converter\LeapYearCalculator;

use PHPUnit_Framework_TestCase;
use Popy\Calendar\Converter\LeapYearCalculator\AgnosticCompleteCalculator;

class AgnosticCompleteCalculatorTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->internal = $this->createMock('Popy\Calendar\Converter\SimpleLeapYearCalculatorInterface');

        $this->calculator = new AgnosticCompleteCalculator(
            $this->internal,
            100 // Use 100-days years to simplify testing
        );
    }

    public function provideLeapYear()
    {
        return [
            [false],
            [true],
        ];
    }

    /**
     * @dataProvider provideLeapYear
     */
    public function testIsLeapYearReturnsInternalCalculatorResult($leap)
    {
        $input = 50;

        $this->internal
            ->expects($this->any())
            ->method('isLeapYear')
            ->with($input)
            ->will($this->returnValue($leap))
        ;

        $this->assertSame($leap, $this->calculator->isLeapYear($input));
    }

    /**
     * @dataProvider provideLeapYear
     */
    public function testGetYearLength($leap)
    {
        $input = 50;

        $this->internal
            ->expects($this->any())
            ->method('isLeapYear')
            ->with($input)
            ->will($this->returnValue($leap))
        ;

        $this->assertSame($leap ? 101 : 100, $this->calculator->getYearLength($input));
    }

    /**
     * @dataProvider provideLeapYear
     */
    public function testGetYearEraDayIndex($leap)
    {
        $input = 50;

        $this->internal
            ->expects($this->any())
            ->method('isLeapYear')
            ->will($this->returnValue($leap))
        ;

        $this->assertSame(
            ($leap ? 101 : 100) * ($input - 1),
            $this->calculator->getYearEraDayIndex($input)
        );
    }

    /** 
     * @dataProvider provideGetYearAndDayIndexFromErayDayIndex
     */
    public function testGetYearAndDayIndexFromErayDayIndex($input, array $leapMap, array $expected)
    {
        $this->internal
            ->expects($this->any())
            ->method('isLeapYear')
            ->will($this->returnValueMap($leapMap))
        ;

        $this->assertSame(
            $expected,
            $this->calculator->getYearAndDayIndexFromErayDayIndex($input)
        );
    }

    public function provideGetYearAndDayIndexFromErayDayIndex()
    {
        yield [
            50,
            [
                [1, false]
            ],
            [1, 50],
        ];
        yield [
            100,
            [
                [1, false],
                [2, false],
            ],
            [2, 0],
        ];
        yield [
            100,
            [
                [1, true],
            ],
            [1, 100],
        ];
        yield [
            -1,
            [
                [0, false]
            ],
            [0, 99],
        ];
        yield [
            -1,
            [
                [0, true]
            ],
            [0, 100],
        ];
    }
}
