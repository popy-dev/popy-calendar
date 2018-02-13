<?php

namespace Popy\Calendar\Tests\Formatter\NumberConverter;

use PHPUnit_Framework_TestCase;
use Popy\Calendar\Formatter\NumberConverter\RFC2550;

class RFC2550Test extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->converter = new RFC2550();
    }

    public function provideTwoWaySamples()
    {
        // 3.1 section
        yield [1, '0001'];
        yield [12, '0012'];
        yield [123, '0123'];
        yield [1234, '1234'];

        // 3.2 section
        yield [12345, 'A12345'];

        // 3.3 section
        yield [123456, 'B123456'];
        yield [12345678, 'D12345678'];
        yield ['999999999999999999999999999999', 'Z999999999999999999999999999999'];

        // 3.4.2.1
        yield ['1000000000000000000000000000000', '^A1000000000000000000000000000000'];
        yield ['99999999999999999999999999999999999999999999999999999999', '^Z99999999999999999999999999999999999999999999999999999999'];

        // 3.4.2.2
        yield ['100000000000000000000000000000000000000000000000000000000', '^^AA100000000000000000000000000000000000000000000000000000000'];

        // 3.4.2.3
        $y = '1' . str_repeat('0', 732);
        yield [$y, '^^^AAA' . $y];

        // 3.5
        yield [0, '/9999'];
        yield [-1, '/9998'];
        yield [-10000, '*Z89999'];
        yield [-99999, '*Z00000'];
        yield [-100000, '*Y899999'];
    }

    /**
     * @dataProvider provideTwoWaySamples
     */
    public function testConverter($year, $formatted)
    {
        $this->assertSame($formatted, $this->converter->to($year));
        $this->assertSame((string)$year, $this->converter->from($formatted));
    }

    /**
     * 3.6 implementation
     */
    public function testOptionnalZeroOnY10kDates()
    {
        $this->assertSame('10000', $this->converter->from('A1'));
        $this->assertSame('1000000000000000000000000000000', $this->converter->from('^A1'));
        $this->assertSame('100000000000000000000000000000000000000000000000000000000', $this->converter->from('^^AA1'));
        $y = '1' . str_repeat('0', 732);
        $this->assertSame($y, $this->converter->from('^^^AAA1'));
    }
}