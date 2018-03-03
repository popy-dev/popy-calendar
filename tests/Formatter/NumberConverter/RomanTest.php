<?php

namespace Popy\Calendar\Tests\Formatter\NumberConverter;

use PHPUnit_Framework_TestCase;
use Popy\Calendar\Formatter\NumberConverter\Roman;

class RomanTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->converter = new Roman();
    }

    public function provideTwoWaySamples()
    {
        return [
            [0   , '0'],
            [1   , 'I'],
            [2   , 'II'],
            [3   , 'III'],
            [4   , 'IV'],
            [5   , 'V'],
            [6   , 'VI'],
            [7   , 'VII'],
            [8   , 'VIII'],
            [9   , 'IX'],
            [10  , 'X'],
            [1000, 'M'],
            [900 , 'CM'],
            [500 , 'D'],
            [400 , 'CD'],
            [100 , 'C'],
            [90  , 'XC'],
            [50  , 'L'],
            [40  , 'XL'],
            [1793, 'MDCCXCIII'],
            [-1793, '-MDCCXCIII'],
        ];
    }

    /**
     * @dataProvider provideTwoWaySamples
     */
    public function testConverter($year, $formatted)
    {
        $this->assertSame($formatted, $this->converter->to($year));
        $this->assertSame($year, $this->converter->from($formatted));
    }

    public function testNonRomanSymbol()
    {
        $this->assertNull($this->converter->from('Xz'));
    }
}
