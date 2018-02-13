<?php

namespace Popy\Calendar\Tests\Formatter\NumberConverter;

use PHPUnit_Framework_TestCase;
use Popy\Calendar\Formatter\NumberConverter\TwoDigitsYear;

class TwoDigitsYearTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->converter = new TwoDigitsYear();
    }

    public function provideTwoWaySamples()
    {
        yield [2000, '00'];
        yield [1990, '90'];
        yield [2010, '10'];
        yield [1950, '50'];
        yield [2049, '49'];
    }

    /**
     * @dataProvider provideTwoWaySamples
     */
    public function testConverter($year, $formatted)
    {
        $this->assertSame($formatted, $this->converter->to($year));
        $this->assertSame($year, $this->converter->from($formatted));
    }
}