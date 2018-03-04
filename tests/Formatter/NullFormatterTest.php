<?php

namespace Popy\Calendar\Tests\Formatter;

use DateTimeImmutable;
use PHPUnit_Framework_TestCase;
use Popy\Calendar\Formatter\NullFormatter;
use Popy\Calendar\ValueObject\DateRepresentation\Date;

class NullFormatterTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->converter = new NullFormatter();
    }

    public function testFormat()
    {
        $input = new DateTimeImmutable();

        $this->assertEquals(
            '',
            $this->converter->format($input, 'Y-m-d H:i:s')
        );
    }
    public function testFormatDateRepresentation()
    {
        $input = Date::buildFromUnixTime(time());

        $this->assertEquals(
            '',
            $this->converter->formatDateRepresentation($input, 'Y-m-d H:i:s')
        );
    }
}
