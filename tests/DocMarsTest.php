<?php

namespace Popy\Calendar\Tests;

use DateTime;
use PHPUnit\Framework\TestCase;
use Popy\Calendar\Factory\ConfigurableFactory;

class DocMarsTest extends TestCase
{
    protected $factory;
    protected $mars;

    public function setUp()
    {
        $this->factory = new ConfigurableFactory();
        $this->mars = $this->factory->build([
            'era_start' => -3029702400,
            'era_start_year' => 1,
            'day_length' => 88775.244,
            'year_length' => 668.5991,
            'leap' => 'float',
            'month' => 'equal_length',
            'month_length' => 30,
            'week' => 'iso',
            'era_start_day_index' => 1,
            'week' => 'simple',
            'week_length' => 10,
        ]);
    }

    public function testMarsTime()
    {
        $this->assertEquals(
            $this->mars->format(
                new DateTime('1873-12-30 00:00:00'),
                "Y-m-d H:i:s"
            ),
            '0001-01-01 23:21:28'
        );

        /**
         * According to http://jtauber.github.io/mars-clock/, the MSD of this date
         * is 51232 sols after 1873-12-29 00:00:00 UTC, which is our era start.
         * That means the internal eraDayIndex should be 51232 aswell, year should be
         * ceil(51232/668.5921) = 77, and day index should be (51232 mod 668.5921) = 419
         */
        $this->assertEquals(
            $this->mars->format(
                new DateTime('2018-02-13 00:00:00 UTC'),
                "Y z"
            ),
            '0077 419'
        );
    }
}
