<?php

namespace Popy\Calendar\Calendar;

use DateTimeZone;
use DateTimeImmutable;
use DateTimeInterface;
use Popy\Calendar\CalendarInterface;

/**
 * Simple implementation of the Warhammer 40k Imperial calendar.
 */
class MankindImperialCalendar implements CalendarInterface
{
    /**
     * @inheritDoc
     */
    public function format(DateTimeInterface $input, $format)
    {
        if (!$input instanceof DateTimeImmutable) {
            $input = DateTimeImmutable::createFromMutable($input);
        }

        // While DateTimeInterface doesn't include date location, assume
        // that it's a terran time.
        $checkNum = 0;
        $fullYear = $input->format('Y');
        $millenium = ceil($fullYear / 1000);
        $year = $fullYear % 1000;

        $ref = $input->modify('first day of January');

        $yearLength = (365 + $input->format('L')) * 24 * 3600;

        $yearFraction = intval(
            1000 * ($input->getTimestamp() - $ref->getTimestamp())
            / $yearLength
        );

        return sprintf(
            '%s%03d%03d.M%02d',
            $checkNum,
            $yearFraction,
            $year,
            $millenium
        );
    }

    /**
     * @inheritDoc
     */
    public function parse($input, $format, DateTimeZone $timezone = null)
    {
        $match = [];

        if (!preg_match('/^(\d)(\d\d\d)(\d\d\d)\.M(\d+)$/', $input, $match)) {
            return null;
        }
        
        $fullYear = $match[4] * 1000 + $match[3];

        $res = DateTimeImmutable::createFromFormat(
            'Y-m-d',
            '2000-01-01'
        );

        $res = $res
            ->modify('+' . ($fullYear - 2000) .'years')
        ;

        $yearLength = (365 + $res->format('L')) * 24 * 3600;
        $seconds = $match[2] * $yearLength / 1000;


        return $res->modify('+' . $seconds . 'seconds');
    }
}
