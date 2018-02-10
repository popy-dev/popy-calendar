<?php

namespace Popy\Calendar\Parser;

use DateTimeZone;
use Popy\Calendar\ParserInterface;

/**
 * NullParser implementation.
 */
class NullParser implements ParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse($input, $format, DateTimeZone $timezone = null)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function parseToDateRepresentation($input, $format, DateTimeZone $timezone = null)
    {
        return null;
    }
}
